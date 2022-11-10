<?php

namespace MGLara\Library\Mercos;

use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use MGLara\Models\MercosPedido as MercosPedidoModel;
use MGLara\Models\MercosPedidoItem as MercosPedidoItemModel;
use MGLara\Models\MercosCliente as MercosClienteModel;
use MGLara\Models\Pessoa;
use MGLara\Models\Negocio;
use MGLara\Models\NegocioProdutoBarra;

class MercosPedido {

    public static function importaPedidoApos ($alterado_apos)
    {

        // busca ultima alteracao importada do mercos
        if (! ($alterado_apos instanceof Carbon)) {
            $alterado_apos = MercosPedidoModel::max('ultimaalteracaomercos');
            if ($alterado_apos != null) {
                $alterado_apos = Carbon::parse($alterado_apos)->addSeconds(1);
            } else {
                $alterado_apos = Carbon::now()->subYear(1);
            }
        }

        $importados = 0;
        $erros = 0;
        $ate = $alterado_apos;

        $api = new MercosApi();
        $peds = $api->getPedidos($alterado_apos);
        foreach ($peds as $ped) {
            // if (empty($ped->)) { continue; }
            $mp = static::parsePedido ($ped);
            if ($mp) {
                $importados++;
                if ($mp->ultimaalteracaomercos > $ate) {
                    $ate = $mp->ultimaalteracaomercos;
                }
            } else {
                $erros++;
            }


        }
        $ret = [
            'importados'=> $importados,
            'erros'=> $erros,
            'ate'=> $ate->format('Y-m-d H:i:s')
        ];
        return $ret;
    }

    public static function parsePedido ($ped)
    {
        DB::BeginTransaction();
        $mp = MercosPedidoModel::firstOrNew([
            'pedidoid' => $ped->id
        ]);
        $mp->numero = $ped->numero;
        $mp->condicaopagamento = $ped->condicao_pagamento;
        $mp->ultimaalteracaomercos = Carbon::createFromFormat('Y-m-d H:i:s', $ped->ultima_alteracao, 'America/Sao_Paulo')->setTimezone('America/Cuiaba');
        $ee = $ped->endereco_entrega;
        $end = '';
        if (!empty($ee->endereco)) {
            $end = [
                $ee->endereco,
                $ee->numero,
                $ee->complemento,
                $ee->bairro,
                $ee->cidade,
                $ee->estado,
                $ee->cep
            ];
            $end = array_filter($end, function($a) {
                return trim($a) !== "";
            });
            $end = implode(', ', $end);
            $mp->enderecoentrega = $end;
        }
        $mp->save();

        if (empty($mp->codnegocio)) {
            $n = new Negocio();
        } else {
            $n = $mp->Negocio;
        }
        $n->codestoquelocal = env('MERCOS_CODESTOQUELOCAL');
        $n->codfilial = $n->EstoqueLocal->codfilial;
        $n->lancamento = $ped->data_criacao;
        $n->codnaturezaoperacao = env('MERCOS_CODNATUREZAOPERACAO');
        $n->codoperacao = $n->NaturezaOperacao->codoperacao;
        $n->codnegociostatus = 1;
        $n->codusuario = env('MERCOS_CODUSUARIO');
        $n->codusuariocriacao = env('MERCOS_CODUSUARIO');
        $n->codusuarioalteracao = env('MERCOS_CODUSUARIO');

        $mc = MercosClienteModel::firstOrNew([
            'clienteid' => $ped->cliente_id,
        ]);

        if (empty($mc->codpessoa) && empty($ped->cliente_cnpj)) {
            $mc->codpessoa = 1; // CONSUMIDOR
            $mc->save();
        } elseif (empty($mc->codpessoa)) {
            $sql = "
                select codpessoa
                from tblpessoa p
                where p.cnpj = :cnpj
            ";
            $params = [
                'cnpj' => $ped->cliente_cnpj
            ];
            $ie = numeroLimpo($ped->cliente_inscricao_estadual);
            if (!empty($ie)) {
                $sql .= " and regexp_replace(p.ie, '[^0-9]+', '', 'g')::numeric = :ie ";
                $params['ie'] = $ie;
            } else {
                $sql .= " and p.ie is null ";
            }
            $ps = DB::select($sql, $params);
            if (isset($ps[0])) {
                $p = Pessoa::findOrFail($ps[0]->codpessoa);
            } else {
                $sql = "
                    select c.codcidade
                    from tblcidade c
                    inner join tblestado e on (e.codestado = c.codestado)
                    where c.cidade ilike :cidade
                    and e.sigla = :estado
                ";
                $cidade = DB::select($sql, [
                    'cidade' => removeAcentos($ped->cliente_cidade),
                    'estado' => removeAcentos($ped->cliente_estado)
                ]);
                if (isset($cidade[0])) {
                    $codcidade = $cidade[0]->codcidade;
                } else {
                    $codcidade = env('CODCIDADE_SINOP');
                }
                $p = new Pessoa([
                    'pessoa' => $ped->cliente_razao_social,
                    'fantasia' => $ped->cliente_nome_fantasia,
                    'cnpj' => $ped->cliente_cnpj,
                    'ie' => $ped->cliente_inscricao_estadual,
                    'endereco' => $ped->cliente_rua,
                    'enderecocobranca' => $ped->cliente_rua,
                    'numero' => $ped->cliente_numero,
                    'numerocobranca' => $ped->cliente_numero,
                    'complemento' => $ped->cliente_complemento,
                    'complementocobranca' => $ped->cliente_complemento,
                    'cep' => $ped->cliente_cep,
                    'cepcobranca' => $ped->cliente_cep,
                    'bairro' => $ped->cliente_bairro,
                    'bairrocobranca' => $ped->cliente_bairro,
                    'bairro' => $ped->cliente_bairro,
                    'codcidade' => $codcidade,
                    'codcidadecobranca' => $codcidade,
                    'contato' => $ped->contato_nome,
                    'notafiscal' => 0,
                ]);
                $p->save();
                // TODO: Buscar dados no Mercos via get para complementar email e telefone
            }
            $mc->codpessoa = $p->codpessoa;
            $mc->save();
        }
        $n->codpessoa = $mc->codpessoa;
        // $n->valortotal = $ped->total;
        $n->valorfrete = $ped->valor_frete;
        $n->save();

        $mp->codnegocio = $n->codnegocio;
        $mp->save();

        foreach ($ped->items as $item) {
            static::parsePedidoItem($item, $n, $mp);
        }

        DB::commit();

        return $mp;
    }

    public static function parsePedidoItem ($item, Negocio $n, MercosPedidoModel $mp)
    {
        $mpi = MercosPedidoItemModel::firstOrNew([
            'itemid' => $item->id,
            'codmercospedido' => $mp->codmercospedido,
        ]);
        if (!empty($mpi->codnegocioprodutobarra)) {
            return $mpi;
        }
        $pb = MercosProduto::procurarProdutoBarra($item->produto_id, $item->produto_codigo);
        if (!$pb) {
            return false;
        }
        $npb = new NegocioProdutoBarra([
            'codnegocio' => $n->codnegocio,
            'codprodutobarra' => $pb->codprodutobarra,
            'quantidade' => $item->quantidade,
            'valorunitario' => $item->preco_liquido,
            'valortotal' => $item->subtotal,
        ]);
        $npb->save();
        $mpi->codnegocioprodutobarra = $npb->codnegocioprodutobarra;
        $mpi->save();
        return $mpi;
    }

}
