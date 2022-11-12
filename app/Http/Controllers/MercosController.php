<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use MGLara\Http\Controllers\Controller;
use MGLara\Library\Mercos\MercosProduto;
use MGLara\Library\Mercos\MercosPedido;
use MGLara\Library\Mercos\MercosCliente;
use MGLara\Models\Negocio;

class MercosController extends Controller
{

    public function exportaProduto(Request $request, $id)
    {
        $codproduto = $id;
        $codprodutovariacao = $request->codprodutovariacao;
        $codprodutoembalagem = empty($request->codprodutoembalagem)?null:$request->codprodutoembalagem;
        $retorno = MercosProduto::exportaProduto(
            $codproduto,
            $codprodutovariacao,
            $codprodutoembalagem
        );
        return response()->json($retorno);
    }

    public function importaPedidoApos (Request $request, $alterado_apos)
    {
        if ($alterado_apos != 'ultima') {
            $alterado_apos = Carbon::createFromFormat('Y-m-d H:i:s', $alterado_apos);
        }
        $retorno = MercosPedido::importaPedidoApos($alterado_apos);
        return response()->json($retorno);
    }

    public function exportaFaturamento(Request $request, $codnegocio)
    {
        $n = Negocio::findOrFail($codnegocio);
        $retorno = MercosPedido::exportaFaturamento($n);
        return response()->json($retorno);
    }

    public function importaClienteApos (Request $request, $alterado_apos)
    {
        if ($alterado_apos != 'ultima') {
            $alterado_apos = Carbon::createFromFormat('Y-m-d H:i:s', $alterado_apos);
        }
        $retorno = MercosCliente::importaClienteApos($alterado_apos);
        return response()->json($retorno);
    }


}
