<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\FormaPagamento;

class FormaPagamentoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $model = FormaPagamento::filterAndPaginate(
            $request->get('codformapagamento'),
            $request->get('formapagamento')
        );
        
        return view('forma-pagamento.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new FormaPagamento();
        return view('forma-pagamento.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new FormaPagamento($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("forma-pagamento/$model->codformapagamento");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = FormaPagamento::find($id);
        return view('forma-pagamento.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = FormaPagamento::findOrFail($id);
        return view('forma-pagamento.edit',  compact('model'));
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
        /*
        $this->atualizaBooleans = [
            'boleto' => $request->get('boleto'),
            'avista' => $request->get('avista'),
            'entrega' => $request->get('entrega'),
            'notafiscal' => $request->get('notafiscal'),
            'fechamento' => $request->get('fechamento')
        ];    
        */
        
        $model = FormaPagamento::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        if($request->input('boleto') == 1)
            $model->boleto = TRUE;
        else
            $model->boleto = FALSE;        

        if($request->input('fechamento') == 1)
            $model->fechamento = TRUE;
        else
            $model->fechamento = FALSE;        
        
        if($request->input('notafiscal') == 1)
            $model->notafiscal = TRUE;
        else
            $model->notafiscal = FALSE;        
        
        if($request->input('entrega') == 1)
            $model->entrega = TRUE;
        else
            $model->entrega = FALSE;        
        
        
        
        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("forma-pagamento/$model->codformapagamento"); 
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
            FormaPagamento::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('forma-pagamento.index');
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }
}
