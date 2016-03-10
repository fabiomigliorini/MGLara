<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueMes;

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
    public function create(Request $request)
    {
        $tipos = EstoqueMovimentoTipo::lists('descricao', 'codestoquemovimentotipo')->all();
        $options = EstoqueMovimentoTipo::all();
        return view('estoque-movimento.create', compact('tipos', 'request', 'options'));
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
        
        $em = EstoqueMes::buscaOuCria(
                $model->EstoqueMes->EstoqueSaldo->codproduto, 
                $model->EstoqueMes->EstoqueSaldo->codestoquelocal, 
                $model->EstoqueMes->EstoqueSaldo->fiscal, 
                $model->data);
        
        $model->codestoquemes = $em->codestoquemes;
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

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
        Input::merge(array('data' => Carbon::createFromFormat(
            'd/m/Y H:i:s', 
            $request->input('data'))->toDateTimeString()
        ));
        $model = EstoqueMovimento::findOrFail($id);
        $model->fill($request->all());
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
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
    public function destroy($id) {

        try{
            EstoqueMovimento::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return redirect("estoque-mes/$model->codestoquemes");
        }
        catch(\Exception $e){
            return view('errors.fk');
        }        
    }
}
