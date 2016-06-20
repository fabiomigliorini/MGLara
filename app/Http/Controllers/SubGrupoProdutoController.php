<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\Produto;
use Carbon\Carbon;

class SubGrupoProdutoController extends Controller
{
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
        $model = new SubGrupoProduto();
        
        return view('sub-grupo-produto.create', compact('model','request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new SubGrupoProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codgrupoproduto = $request->get('codgrupoproduto');
        $model->save();
        
        Session::flash('flash_success', 'Sub Grupo Criada!');
        return redirect("sub-grupo-produto/$model->codsubgrupoproduto");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = SubGrupoProduto::findOrFail($id);
        $produtos = Produto::where('codsubgrupoproduto', 10005)->paginate(10);
        return view('sub-grupo-produto.show', compact('model', 'produtos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = SubGrupoProduto::findOrFail($id);
        return view('sub-grupo-produto.edit',  compact('model'));
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
        $model = SubGrupoProduto::findOrFail($id);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', "Sub Grupo '{$model->subgrupoproduto}' Atualizado!");
        return redirect("sub-grupo-produto/$model->codsubgrupoproduto");         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $model = SubGrupoProduto::find($id);
            $model->delete();
            Session::flash('flash_success', "Sub Grupo '{$model->subgrupoproduto}' Excluido!");
            return redirect("grupo-produto/$model->codgrupoproduto");
        }
        catch(\Exception $e){
            Session::flash('flash_danger', "Impossível Excluir!");
            Session::flash('flash_danger_detail', $e->getMessage());
            return redirect("sub-grupo-produto/$id"); 
        }     
    }
    
    public function inativo(Request $request)
    {
        $model = SubGrupoProduto::find($request->get('codsubgrupoproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Sub Grupo '{$model->subgrupoproduto}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Sub Grupo '{$model->subgrupoproduto}' Inativado!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }    

}
