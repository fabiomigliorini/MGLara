<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Estado;
use MGLara\Models\Cidade;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Estado();
        return view('estado.create', compact('model', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Estado($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codpais = $request->get('codpais');
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("pais/$model->codpais");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = Estado::find($id);
        $cidades = Cidade::filterAndPaginate(
            $model->codestado, 
            $request->get('codcidade'), 
            $request->get('cidade'), 
            $request->get('sigla'),
            $request->get('codigooficial')
        );
        return view('estado.show', compact('model', 'cidades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Estado::findOrFail($id);
        return view('estado.edit',  compact('model'));
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
        $model = Estado::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("estado/$model->codestado"); 
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
            $model = Estado::find($id);
            $model->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return redirect("pais/$model->codpais");
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }
}
