<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Models\GrupoProduto;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueLocal;

class GrupoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = GrupoProduto::filterAndPaginate(
            $request->get('codgrupoproduto'),
            $request->get('grupoproduto')    
        );         
        $ess = EstoqueSaldo::saldoPorGrupoProduto();
        $els = EstoqueLocal::where('inativo', null)->orderBy('codestoquelocal')->get();
        return view('grupo-produto.index', compact('model', 'ess', 'els'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = GrupoProduto::class;
        return view('grupo-produto.create', compact('model'));
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
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("grupo-produto/$model->codgrupoproduto");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = GrupoProduto::findOrFail($id);
        $ess = EstoqueSaldo::saldoPorSubGrupoProduto($model->codgrupoproduto);
        $els = EstoqueLocal::where('inativo', null)->orderBy('codestoquelocal')->get();
        return view('grupo-produto.show', compact('model', 'ess', 'els'));
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
        Session::flash('flash_update', 'Registro atualizado.');
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
	        GrupoProduto::find($id)->delete();
	        Session::flash('flash_delete', 'Registro deletado!');
	        return Redirect::route('grupo-produto.index');
        }
        catch(\Exception $e){
        	return view('errors.fk');
        }     
    }

    public function buscaCodProduto($id)
    {
        $model = GrupoProduto::findOrFail($id);
        foreach ($model->SubGrupoProdutoS as $sg)
            foreach ($sg->ProdutoS as $prod)
                $arr_codproduto[] = $prod->codproduto;
        echo json_encode($arr_codproduto);        
    }
    
}
