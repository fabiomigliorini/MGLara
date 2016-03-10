<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Produto;
use MGLara\Models\NegocioProdutoBarra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        return json_encode($ret);
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
        return json_encode($ret);
    }
    
    /**
     * Tenta cobrir estoque negativo, transferindo entre EstoqueLocal
     * 
     * @param bigint $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function cobreEstoqueNegativo($id = null)
    {
        $codprodutos = [];
        if (empty($id))
        {
            $pular = 0;
            
            if (isset($_GET['pular']))
                $pular = $_GET['pular'];
            
            $itens = 10;
            if (isset($_GET['itens']))
                $itens = $_GET['itens'];
            
            $sql = "
                    select distinct(es.codproduto) 
                    from tblestoquesaldo es
                    where es.fiscal
                    and es.saldoquantidade < 0
                    and es.codproduto in (select distinct es2.codproduto from tblestoquesaldo es2 where es2.fiscal and es2.saldoquantidade > 0)
                    order by es.codproduto
                    limit $itens
                    offset $pular
                    ";
            
            $prods = DB::select($sql);
            
            foreach($prods as $prod)
                $codprodutos[] = $prod->codproduto;
            
        }
        else
        {
            $codprodutos[] = $id;
        }
        
        $ret = [];
        foreach ($codprodutos as $codproduto)
        {
            $model = Produto::findOrFail($codproduto);
            $ret[$codproduto] = $model->cobreEstoqueNegativo();
        }
        
        return json_encode($ret);
    }
    
    
}
