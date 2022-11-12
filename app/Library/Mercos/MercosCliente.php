<?php

namespace MGLara\Library\Mercos;

use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use MGLara\Models\MercosCliente as MercosClienteModel;
use MGLara\Models\Pessoa;
use MGLara\Models\Negocio;
use MGLara\Models\NegocioProdutoBarra;

class MercosCliente {

    public static function importaClienteApos ($alterado_apos)
    {

        // busca ultima alteracao importada do mercos
        if (! ($alterado_apos instanceof Carbon)) {
            $alterado_apos = MercosClienteModel::max('ultimaalteracaomercos');
            if ($alterado_apos != null) {
                $alterado_apos = Carbon::parse($alterado_apos)->addSeconds(1);
            } else {
                $alterado_apos = Carbon::now()->subYear(1);
            }
        }

        $importados = 0;
        $ignorados = 0;
        $erros = 0;
        $ate = $alterado_apos;

        $api = new MercosApi();
        $clis = $api->getClientes($alterado_apos);

        foreach ($clis as $cli) {
            if ($cli->excluido) {
                $ignorados ++;
                continue;
            }
            $mp = static::parseCliente ($cli);
            if (!$mp) {
                $erros++;
                continue;
            }
            $importados++;
            if ($mp->ultimaalteracaomercos > $ate) {
                $ate = $mp->ultimaalteracaomercos;
            }
        }
        $ret = [
            'importados'=> $importados,
            'erros'=> $erros,
            'ate'=> $ate->format('Y-m-d H:i:s')
        ];
        return $ret;
    }

    public static function buscaPessoa ($cnpj, $ie)
    {
        if (empty($cnpj)) {
            return null;
        }
        $sql = "
            select codpessoa
            from tblpessoa p
            where p.inativo is null
            and p.cnpj = :cnpj
        ";
        $params = [
            'cnpj' => intval($cnpj)
        ];
        $ie = numeroLimpo($ie);
        if (!empty($ie)) {
            $sql .= " and regexp_replace(p.ie, '[^0-9]+', '', 'g')::numeric = :ie ";
            $params['ie'] = $ie;
        } else {
            $sql .= " and p.ie is null ";
        }
        $ps = DB::select($sql, $params);
        if (isset($ps[0])) {
            return Pessoa::findOrFail($ps[0]->codpessoa);
        }
        return null;
    }

    public static function parseCliente ($cli)
    {
        if ($cli->excluido) {
            return null;
        }
        DB::BeginTransaction();
        $mc = MercosClienteModel::firstOrNew([
            'clienteid' => $cli->id
        ]);
        if ((empty($mc->codpessoa) || ($mc->cliente == 1))) {
            $p = static::buscaPessoa($cli->cnpj, $cli->inscricao_estadual);
            if ($p == null) {
                $p = new Pessoa();
            }
        } else {
            $p = $mc->Pessoa;
        }
        $p->cnpj = $cli->cnpj;
        $p->ie = $cli->inscricao_estadual;

        $p->pessoa =  $cli->razao_social;
        $p->fantasia = $cli->nome_fantasia;
        $p->fisica = ($cli->tipo == 'F')?true:false;
        $p->cnpj = $cli->cnpj;
        $p->ie = $cli->inscricao_estadual;

        $p->endereco = $cli->rua;
        $p->numero = $cli->numero;
        $p->complemento = $cli->complemento;
        $p->cep = $cli->cep;
        $p->bairro = $cli->bairro;
        $sql = "
            select c.codcidade
            from tblcidade c
            inner join tblestado e on (e.codestado = c.codestado)
            where c.cidade ilike :cidade
            and e.sigla = :estado
        ";
        $cidade = DB::select($sql, [
            'cidade' => removeAcentos($cli->cidade),
            'estado' => removeAcentos($cli->estado)
        ]);
        if (isset($cidade[0])) {
            $codcidade = $cidade[0]->codcidade;
        } else {
            $codcidade = env('CODCIDADE_SINOP');
        }
        $p->codcidade = $codcidade;

        if (empty($p->enderecocobranca)) {
            $p->enderecocobranca = $p->endereco;
            $p->numerocobranca = $p->numero;
            $p->complementocobranca = $p->complemento;
            $p->cepcobranca = $p->cep;
            $p->bairrocobranca = $p->bairro;
            $p->codcidadecobranca = $p->codcidade;
        }

        if (!empty($cli->observacao)) {
            if (!strstr($p->observacoes, $cli->observacao )) {
                if (!empty($p->observacoes)) {
                    $p->observacoes .= "\n";
                }
                $p->observacoes .= $cli->observacao;
            }
        }

        foreach ($cli->emails as $email) {
            if (in_array($email->email, [$p->email, $p->emailnfe, $p->emailcobranca])) {
                continue;
            }
            if (empty($p->email)) {
                $p->email = $email->email;
                continue;
            }
            if (empty($p->emailnfe)) {
                $p->emailnfe = $email->email;
                continue;
            }
            if (empty($p->emailcobranca)) {
                $p->emailcobranca = $email->email;
                continue;
            }
            if (empty($p->codpessoa)) {
                $p->observacoes .= "\nEmail: {$email->email}";
            }
        }

        foreach ($cli->telefones as $telefone) {
            if (in_array(
                intval(NumeroLimpo($telefone->numero)), [
                    intval(NumeroLimpo($p->telefone1)),
                    intval(NumeroLimpo($p->telefone2)),
                    intval(NumeroLimpo($p->telefone3))
                ])) {
                continue;
            }
            if (empty($p->telefone1)) {
                $p->telefone1 = $telefone->numero;
                continue;
            }
            if (empty($p->telefone2)) {
                $p->telefone2 = $telefone->numero;
                continue;
            }
            if (empty($p->telefone3)) {
                $p->telefone3 = $telefone->numero;
                continue;
            }
            if (empty($p->codpessoa)) {
                $p->observacoes .= "\nFone: {$telefone->numero}";
            }
        }

        foreach ($cli->contatos as $contato) {
            $p->contato = $contato->nome;
        }

        // $p->contato = $cli->contato_nome;
        $p->notafiscal = 0;
        if (empty($p->fantasia)) {
            $p->fantasia = $p->pessoa;
        }

        $p->observacoes = substr($p->observacoes, 0, 500);
        $p->cliente = true;
        $p->save();

        $mc->codpessoa = $p->codpessoa;
        $mc->ultimaalteracaomercos = Carbon::createFromFormat('Y-m-d H:i:s', $cli->ultima_alteracao, 'America/Sao_Paulo')->setTimezone('America/Cuiaba');
        $mc->save();

        DB::commit();

        return $mc;
    }


}
