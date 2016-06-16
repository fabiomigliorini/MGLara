<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use Carbon\Carbon;

class SecaoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $model = SecaoProduto::filterAndPaginate(
            $request->get('codsecaoproduto'),
            $request->get('secaoproduto'),
            $request->get('inativo')
        );
        
        return view('secao-produto.index', compact('model'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new SecaoProduto();
        return view('secao-produto.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new SecaoProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("secao-produto/$model->codsecaoproduto");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = SecaoProduto::find($id);
        $familias = FamiliaProduto::filterAndPaginate(
            $request->get('codfamiliaproduto'),
            $id,
            $request->get('familiaproduto'), 
            $request->get('inativo')
        );
        return view('secao-produto.show', compact('model', 'familias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = SecaoProduto::findOrFail($id);
        return view('secao-produto.edit',  compact('model'));
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
        $model = SecaoProduto::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("secao-produto/$model->codsecaoproduto"); 
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
            SecaoProduto::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('secao-produto.index');
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }
    
    public function inativo(Request $request)
    {
        $model = SecaoProduto::find($request->get('codsecaoproduto'));
        if($request->get('acao') == 'ativar')
            $model->inativo = null;
        else
            $model->inativo = Carbon::now();
        
        $model->save();
    }    
    
}
