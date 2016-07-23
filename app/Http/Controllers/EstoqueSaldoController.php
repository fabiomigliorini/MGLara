<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

class EstoqueSaldoController extends Controller
{

    public function index(Request $request)
    {
        $parametros = $request->all();
        if (!empty($request->codproduto)) {
            $parametros['codproduto'] = $request->codproduto;
            $agrupamento = 'variacao';
            $link = null;
        } else if ((!empty($request->codmarca)) && (!empty($request->codsubgrupoproduto))) {
            $parametros['codmarca'] = $request->codmarca;
            $parametros['codsubgrupoproduto'] = $request->codsubgrupoproduto;
            $agrupamento = 'produto';
            $link = url('estoque-saldo/?codproduto=');
        } else if (!empty($request->codsubgrupoproduto)) {
            $parametros['codsubgrupoproduto'] = $request->codsubgrupoproduto;
            $agrupamento = 'marca';
            $link = url("estoque-saldo/?codsubgrupoproduto={$request->codsubgrupoproduto}&codmarca=");
        } else if (!empty($request->codgrupoproduto)) {
            $parametros['codgrupoproduto'] = $request->codgrupoproduto;
            $agrupamento = 'subgrupoproduto';
            $link = url('estoque-saldo/?codsubgrupoproduto=');
        } else if (!empty($request->codfamiliaproduto)) {
            $parametros['codfamiliaproduto'] = $request->codfamiliaproduto;
            $agrupamento = 'grupoproduto';
            $link = url('estoque-saldo/?codgrupoproduto=');
        } else if (!empty($request->codsecaoproduto)) {
            $parametros['codsecaoproduto'] = $request->codsecaoproduto;
            $agrupamento = 'familiaproduto';
            $link = url('estoque-saldo/?codfamiliaproduto=');
        } else {
            $agrupamento = 'secaoproduto';
            $link = url('estoque-saldo/?codsecaoproduto=');
        }
        $itens = EstoqueSaldo::totais($agrupamento, $parametros);
        return view('estoque-saldo.index', compact('itens', 'link'));
    }
    
    /**
     * Redireciona para último EstoqueMes encontrado
     *
     * @param  bigint  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ems = EstoqueMes::where('codestoquesaldo', $id)
               ->orderBy('mes', 'DESC')
               ->take(1)
               ->get();
        return redirect("estoque-mes/{$ems[0]->codestoquemes}");
    }
    
    public function zera($id)
    {
        $model = EstoqueSaldo::findOrFail($id);
        return json_encode($model->zera());
    }
    
    public function relatorio(Request $request)
    {
        return view('estoque-saldo.relatorio', []);
    }

}
