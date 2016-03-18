<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueLocal;

class SubGrupoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        $model = SubGrupoProduto::filterAndPaginate(
            $request->get('codsubgrupoproduto'),
            $request->get('subgrupoproduto')    
        );
        */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = SubGrupoProduto::class;
        
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
        Session::flash('flash_create', 'Registro inserido.');
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
        $ess = EstoqueSaldo::saldoPorProduto($model->codsubgrupoproduto);
        $els = EstoqueLocal::where('inativo', null)->orderBy('codestoquelocal')->get();
        return view('sub-grupo-produto.show', compact('model', 'ess', 'els'));
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
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("sub-grupo-produto/$id");
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
            Session::flash('flash_delete', 'Registro deletado!');
            return redirect("grupo-produto/$model->codgrupoproduto");

        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }
    
    public function buscaCodProduto($id)
    {
        $model = SubGrupoProduto::findOrFail($id);
        foreach ($model->ProdutoS as $prod)
            $arr_codproduto[] = $prod->codproduto;
        echo json_encode($arr_codproduto);        
    }
}
