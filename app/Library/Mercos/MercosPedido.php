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
        if (empty($n->lancamento)) {
            $n->lancamento = $ped->ultima_alteracao;
        }
        $n->codnaturezaoperacao = env('MERCOS_CODNATUREZAOPERACAO');
        $n->codoperacao = $n->NaturezaOperacao->codoperacao;
        $n->codnegociostatus = 1;
        $n->codusuario = env('MERCOS_CODUSUARIO');
        $n->codusuariocriacao = env('MERCOS_CODUSUARIO');
        $n->codusuarioalteracao = env('MERCOS_CODUSUARIO');

        $mc = MercosCliente::buscaOuCriaPeloId($ped->cliente_id);
        $n->codpessoa = $mc->codpessoa;
        // $n->valortotal = $ped->total;
        $n->valorfrete = $ped->valor_frete;
        $n->save();

        $mp->codnegocio = $n->codnegocio;
        $mp->save();

        foreach ($ped->itens as $item) {
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
        if ($item->excluido) {
            return;
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

    public static function exportaFaturamento (Negocio $n)
    {
        $ret = [];
        if ($n->codnegociostatus != 2) {
            return $ret;
        }
        $api = new MercosApi();
        foreach ($n->MercosPedidoS as $mp) {
            if (empty($mp->faturamentoid)) {
                $api->postFaturamento(
                    $mp->pedidoid,
                    $n->valortotal,
                    $n->lancamento,
                    null,
                    'Negocio ' . formataCodigo($n->codnegocio)
                );
                $mp->faturamentoid = $api->headers['meuspedidosid'];
                $mp->save();
                $ret[] = $mp->faturamentoid;
            } else {
                $api->putFaturamento(
                    $mp->faturamentoid,
                    $mp->pedidoid,
                    $n->valortotal,
                    $n->lancamento,
                    null,
                    'Negocio ' . formataCodigo($n->codnegocio)
                );
                $ret[] = $mp->faturamentoid;
            }
        }
        return $ret;
    }

}
