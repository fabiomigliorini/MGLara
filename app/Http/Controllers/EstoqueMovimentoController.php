<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class EstoqueMovimentoController extends Controller
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
    public function create()
    {
        $tipos = EstoqueMovimentoTipo::lists('descricao', 'codestoquemovimentotipo')->all();
        return view('estoque-movimento.create', compact('tipos'/*, 'filiais', 'ecfs', 'ops', 'portadores', 'prints'*/));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Input::merge(array('data' => Carbon::createFromFormat(
            'd/m/Y H:i:s', 
            $request->input('data'))->toDateTimeString()
        ));
        $model = new EstoqueMovimento($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->codestoquemes = 3930;
        $model->manual = TRUE;
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("estoque-mes/$model->codestoquemes");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = EstoqueMovimento::find($id);
        return view('estoque-movimento.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = EstoqueMovimento::findOrFail($id);
        $tipos = EstoqueMovimentoTipo::lists('descricao', 'codestoquemovimentotipo')->all();
        return view('estoque-movimento.edit',  compact('model', 'tipos'));        
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
        $model = EstoqueMovimento::findOrFail($id);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("estoque-mes/$model->codestoquemes");
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
