<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Produto;
use MGLara\Models\NegocioProdutoBarra;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    
    public function __construct()
    {
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo 'index';
        die();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $model = Produto::find($id);
        //$teste = NegocioProdutoBarra::containt();
        //$negocios = NegocioProdutoBarra::negocioPorProduto($id);
        return view('produto.show', compact('model'));
    }


    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function recalculaMovimentoEstoque($id)
    {
        $model = Produto::findOrFail($id);
        $ret = $model->recalculaMovimentoEstoque();
        echo json_encode($ret);
    }
    
    /**
     * Recalcula preço médio dos estoques
     * 
     * @param bigint $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function recalculaCustoMedio($id)
    {
        $model = Produto::findOrFail($id);
        $ret = $model->recalculaCustoMedio();
        echo json_encode($ret);
    }
    
}
