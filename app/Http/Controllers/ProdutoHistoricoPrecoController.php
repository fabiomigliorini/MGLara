<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\ProdutoHistoricoPreco;

class ProdutoHistoricoPrecoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $parametros = self::filtroEstatico(
            $request, 
            'produto-historico-preco.index', 
            [], 
            ['alteracao_de', 'alteracao_ate']
        );
        
        $model = ProdutoHistoricoPreco::search($parametros)->orderBy('criacao', 'DESC')->paginate(20);
        return view('produto-historico-preco.index', compact('model'));
    }    

    public function relatorioFiltro(Request $request)
    {
        
        $filtro = $request->all();
        
        return view('produto-historico-preco.relatorio-filtro', compact('filtro'));
    }
    public function relatorio(Request $request)
    {
        $parametros = $request->all();
        
        $dados = ProdutoHistoricoPreco::search($parametros)->orderBy('criacao', 'DESC')->get();
        
        return view('produto-historico-preco.relatorio', compact('dados'));
    }}
