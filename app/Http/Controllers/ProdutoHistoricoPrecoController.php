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
        
        $model = ProdutoHistoricoPreco::search($parametros);
        return view('produto-historico-preco.index', compact('model'));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
