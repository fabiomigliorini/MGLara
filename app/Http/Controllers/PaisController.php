<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Pais;
use MGLara\Models\Estado;

class PaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $parametros = $request->session()->get('MGLara\Http\Middleware\ParametrosGet|pais');
        
        $model = Pais::filterAndPaginate(
            $request->session()->get('pais')['codpais'],
            $request->session()->get('pais')['pais']
        );
        
        return view('pais.index', compact('model', 'parametros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Pais();
        return view('pais.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Pais($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
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
        $model = Pais::find($id);
        $estados = Estado::filterAndPaginate(
            $model->codpais, 
            $request->get('codestado'), 
            $request->get('estado'), 
            $request->get('sigla'),
            $request->get('codigooficial')
        );
        return view('pais.show', compact('model', 'estados'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Pais::findOrFail($id);
        return view('pais.edit',  compact('model'));
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
        $model = Pais::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("pais/$model->codpais"); 
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
            Pais::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('pais.index');
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }
}
