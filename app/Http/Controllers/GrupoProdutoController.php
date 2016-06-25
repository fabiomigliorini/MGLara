<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;
use Carbon\Carbon;

class GrupoProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['show']]);
    }      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new GrupoProduto();
        $parent = FamiliaProduto::findOrFail($request->get('codfamiliaproduto'));
        return view('grupo-produto.create', compact('model', 'parent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new GrupoProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codfamiliaproduto = $request->get('codfamiliaproduto');
        $model->save();
        
        Session::flash('flash_success', 'Grupo Criado!');
        return redirect("grupo-produto/$model->codgrupoproduto");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$request->session()->has('grupo-produto.show'))
            $request->session()->put("grupo-produto.show.inativo", '1');
        
        $request->session()->put("grupo-produto.show.codgrupoproduto", $id);
        $parametros = $request->session()->get('grupo-produto.show');               
            
        $model = GrupoProduto::findOrFail($id);
        $subgrupos = SubGrupoProduto::search($parametros);
        return view('grupo-produto.show', compact('model','subgrupos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = GrupoProduto::findOrFail($id);
        return view('grupo-produto.edit',  compact('model'));
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
        $model = GrupoProduto::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', "Grupo '{$model->grupoproduto}' Atualizado!");
        return redirect("grupo-produto/$id");   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $i
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $model = SubGrupoProduto::find($id);
            $model->delete();
            Session::flash('flash_success', "Sub Grupo '{$model->subgrupoproduto}' Excluido!");
            return redirect("familia-produto/$model->codfamiliaproduto");
        }
        catch(\Exception $e){
            Session::flash('flash_danger', "Impossível Excluir!");
            Session::flash('flash_danger_detail', $e->getMessage());
            return redirect("grupo-produto/$id"); 
        }     
    }
    
    public function inativo(Request $request)
    {
        $model = GrupoProduto::find($request->get('codgrupoproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Grupo '{$model->grupoproduto}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Grupo '{$model->grupoproduto}' Inativado!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }    
    
}
