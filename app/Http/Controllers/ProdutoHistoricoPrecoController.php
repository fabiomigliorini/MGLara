<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;

use MGLara\Models\ProdutoHistoricoPreco;

class ProdutoHistoricoPrecoController extends Controller
{
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['index']]);
    }       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        if (!$request->session()->has('produto-historico-preco.index')) 
            $request->session()->put('produto-historico-preco.index.codusuario', '');
        
        $parametros = $request->session()->get('produto-historico-preco.index');        

        if (!empty($parametros['alteracao_de'])) {
            $parametros['alteracao_de'] = new Carbon($parametros['alteracao_de']);
        }
        
        if (!empty($parametros['alteracao_ate'])) {
            $parametros['alteracao_ate'] = new Carbon($parametros['alteracao_ate'] . ' 23:59:59');
        }
        
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
