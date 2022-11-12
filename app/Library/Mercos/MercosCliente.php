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
        // $str = '[{"ultima_alteracao":"2022-10-31 11:05:56","razao_social":"Comércio de Gêneros Alimentícios LTDA","nome_fantasia":"Supermercado do Bairro [exemplo]","tipo":"J","cnpj":"","inscricao_estadual":"","suframa":"","rua":"Av. Raimundo Pereira de Magalhães, 450 - Lapa","numero":null,"complemento":"","cep":"","bairro":"","cidade":"São Paulo","estado":"SP","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6814472,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[{"nome":"João da Silva [exemplo]","cargo":null,"excluido":false,"id":2942580,"telefones":[],"emails":[]}]},{"ultima_alteracao":"2022-11-04 15:35:09","razao_social":"Hilario Renato Piccini e Outros","nome_fantasia":"","tipo":"F","cnpj":null,"inscricao_estadual":"13.239.176-7","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":true,"bloqueado_b2b":false,"id":6820636,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511713},{"tipo":"T","email":"marcos@zestore.com.br","id":13511714}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23826885},{"tipo":"T","numero":"(47) 9876-5432","id":23826886}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 15:34:23","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719001},{"ultima_alteracao":"2022-11-04 15:34:23","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719002}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":true,"id":2954970,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826879}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511706}]},{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":true,"id":2954971,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826884}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511712}]},{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2954972,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826887}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511715}]}]},{"ultima_alteracao":"2022-11-04 15:35:12","razao_social":"Hilario Renato Piccini e Outros","nome_fantasia":"Fazenda Monte Cristo","tipo":"F","cnpj":"22481826949","inscricao_estadual":"13.239.176-7","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":true,"bloqueado_b2b":false,"id":6820637,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511717},{"tipo":"T","email":"marcos@zestore.com.br","id":13511718}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23826889},{"tipo":"T","numero":"(47) 9876-5432","id":23826890}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 15:34:52","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719003},{"ultima_alteracao":"2022-11-04 15:34:52","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719004}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2954973,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826888}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511716}]}]},{"ultima_alteracao":"2022-11-04 15:35:15","razao_social":"3a36db67e16b49fa","nome_fantasia":null,"tipo":"J","cnpj":"62339166000194","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":true,"bloqueado_b2b":false,"id":6820173,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-04 15:35:18","razao_social":"5204b29748e64dcc","nome_fantasia":null,"tipo":"J","cnpj":"70292765000128","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":true,"bloqueado_b2b":false,"id":6820172,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-04 15:35:21","razao_social":"dde901f806904502","nome_fantasia":null,"tipo":"J","cnpj":"93806166000180","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":true,"bloqueado_b2b":false,"id":6820174,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-04 15:35:25","razao_social":"Hilario Renato Piccini e Outros","nome_fantasia":"Fazenda Santa Clara","tipo":"F","cnpj":"","inscricao_estadual":"13.463.401-2","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":true,"bloqueado_b2b":false,"id":6820635,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511704},{"tipo":"T","email":"marcos@zestore.com.br","id":13511705}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23826877},{"tipo":"T","numero":"(47) 9876-5432","id":23826878}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 15:30:48","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4718999},{"ultima_alteracao":"2022-11-04 15:30:48","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719000}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2954969,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826876}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511703}]}]},{"ultima_alteracao":"2022-11-04 15:35:28","razao_social":"Loja do Zé LTDA","nome_fantasia":"Zé Store","tipo":"J","cnpj":"46487899000110","inscricao_estadual":"ISENTO","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":true,"bloqueado_b2b":false,"id":6820622,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511689},{"tipo":"T","email":"marcos@zestore.com.br","id":13511690}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23826862},{"tipo":"T","numero":"(47) 9876-5432","id":23826863}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 15:28:05","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4718997},{"ultima_alteracao":"2022-11-04 15:28:05","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4718998}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2954956,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826861}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511688}]}]},{"ultima_alteracao":"2022-11-04 15:39:54","razao_social":"Hilario Renato Piccini e Outros","nome_fantasia":"Fazenda Santa Clara","tipo":"F","cnpj":"22481826949","inscricao_estadual":"13.463.401-2","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":false,"bloqueado_b2b":false,"id":6820653,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511749},{"tipo":"T","email":"marcos@zestore.com.br","id":13511750}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23826922},{"tipo":"T","numero":"(47) 9876-5432","id":23826923}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 15:39:54","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719007},{"ultima_alteracao":"2022-11-04 15:39:54","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719008}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2954976,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826921}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511748}]}]},{"ultima_alteracao":"2022-11-04 15:40:10","razao_social":"Hilario Renato Piccini e Outros","nome_fantasia":"Fazenda Santa Clara","tipo":"F","cnpj":"22481826949","inscricao_estadual":"13.463.401-2","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":false,"bloqueado_b2b":false,"id":6820654,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511752},{"tipo":"T","email":"marcos@zestore.com.br","id":13511753}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23826925},{"tipo":"T","numero":"(47) 9876-5432","id":23826926}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 15:40:10","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719009},{"ultima_alteracao":"2022-11-04 15:40:10","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719010}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2954977,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826924}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511751}]}]},{"ultima_alteracao":"2022-11-04 16:36:00","razao_social":"Hilario Renato Piccini e Outros","nome_fantasia":"","tipo":"F","cnpj":"22481826949","inscricao_estadual":"13.239.176-7","suframa":"","rua":"Rua Abdon Batista","numero":"121","complemento":"sala 1402","cep":"89201010","bairro":"Centro","cidade":"Joinville","estado":"SC","observacao":"Cliente com ótimo histórico de pagamentos.","excluido":false,"bloqueado_b2b":false,"id":6820639,"nome_excecao_fiscal":"Isento","emails":[{"tipo":"T","email":"jose@zestore.com.br","id":13511869},{"tipo":"T","email":"marcos@zestore.com.br","id":13511870},{"tipo":"T","email":"fabio@mgpapelaria.com.br","id":13511871}],"criador_id":58731,"telefones":[{"tipo":"T","numero":"(11) 98765-4321","id":23827045},{"tipo":"T","numero":"(47) 9876-5432","id":23827046}],"enderecos_adicionais":[{"ultima_alteracao":"2022-11-04 16:35:59","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"277","complemento":"","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719005},{"ultima_alteracao":"2022-11-04 16:35:59","cep":"89223005","endereco":"Av. Rolf Wiest","numero":"333","complemento":"Sala 1","bairro":"Bom Retiro","cidade":"Joinville","estado":"SC","id":4719006}],"contatos":[{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":true,"id":2954974,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23826892}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511721}]},{"nome":"Lucas da Silva","cargo":"Gerente de Compras","excluido":false,"id":2955053,"telefones":[{"tipo":"T","numero":"(21) 1111-1234","id":23827047}],"emails":[{"tipo":"T","email":"lucas@zestore.com.br","id":13511872}]}]},{"ultima_alteracao":"2022-11-09 17:09:45","razao_social":"teste 01","nome_fantasia":"teste 01","tipo":"J","cnpj":"67811404000136","inscricao_estadual":"","suframa":"","rua":"Avenida dos Flamboyants","numero":"701","complemento":"","cep":"78557640","bairro":"Jardim Jacarandás","cidade":"Sinop","estado":"MT","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834383,"emails":[{"tipo":"T","email":"vavalindow@gmail.com","id":13522129}],"criador_id":58813,"telefones":[{"tipo":"T","numero":"66999999999","id":23842640}],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-09 17:21:22","razao_social":"Matheus Nascimento","nome_fantasia":"Matheus Nascimento","tipo":"F","cnpj":"06165639143","inscricao_estadual":"","suframa":"","rua":"Rua dos Pessegueiros","numero":"607","complemento":"Barraco","cep":"78556632","bairro":"Jardim Celeste","cidade":"Sinop","estado":"MT","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834408,"emails":[{"tipo":"T","email":"asdrogi@gmail.com","id":13522166}],"criador_id":58813,"telefones":[{"tipo":"T","numero":"66996464284","id":23842677}],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 10:48:14","razao_social":"8a29dfdddc434a42","nome_fantasia":null,"tipo":"J","cnpj":"47317576000140","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834686,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 11:24:58","razao_social":"4065801a7f504c00","nome_fantasia":null,"tipo":"J","cnpj":"96676941000119","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834751,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 11:29:20","razao_social":"10533634ea174f1b","nome_fantasia":null,"tipo":"J","cnpj":"29475868000120","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834764,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 11:29:20","razao_social":"942b6e648fd34cb6","nome_fantasia":null,"tipo":"J","cnpj":"24393788000101","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834765,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 11:29:20","razao_social":"c8d813338f594112","nome_fantasia":null,"tipo":"J","cnpj":"70163261000108","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834766,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 11:29:20","razao_social":"5d19f51a2e0541db","nome_fantasia":null,"tipo":"J","cnpj":"16586626000161","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834767,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]},{"ultima_alteracao":"2022-11-10 11:29:20","razao_social":"bb4c25f56391498b","nome_fantasia":null,"tipo":"J","cnpj":"39037290000183","inscricao_estadual":"","suframa":"","rua":null,"numero":null,"complemento":"","cep":"","bairro":"","cidade":"","estado":"","observacao":"","excluido":false,"bloqueado_b2b":false,"id":6834768,"emails":[],"telefones":[],"enderecos_adicionais":[],"contatos":[]}]';
        // $clis = json_decode($str);
        $clis = $api->getClientes($alterado_apos);
        // dd($api->response);
        // dd($clis);

        // dd($clis);

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
