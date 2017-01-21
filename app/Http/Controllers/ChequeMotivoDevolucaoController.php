<?php

namespace MGLara\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\ChequeMotivoDevolucao;

class ChequeMotivoDevolucaoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $parametros = self::filtroEstatico($request, 'cheque-motivo-devolucao.index', []);
        $model = ChequeMotivoDevolucao::search($parametros)
            ->orderBy('numero', 'ASC')
            ->paginate(20);
        return view('cheque-motivo-devolucao.index', compact('model'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $model = new ChequeMotivoDevolucao();
        return view('cheque-motivo-devolucao.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $dados = $request->all();
        $model = new ChequeMotivoDevolucao($dados);

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        $model->save();
        Session::flash('flash_success', "Motivo de Devolução de Cheque '{$model->chequemotivodevolucao}' criado!");

        return redirect("cheque-motivo-devolucao/$model->codchequemotivodevolucao");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $model = ChequeMotivoDevolucao::findOrFail($id);
        return view('cheque-motivo-devolucao.show', compact('model'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $model = ChequeMotivoDevolucao::findOrFail($id);

        return view('cheque-motivo-devolucao.edit',  compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $dados = $request->all();
        $model = ChequeMotivoDevolucao::findOrFail($id);
        $model->fill($dados);
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }


        $model->save();
        Session::flash('flash_success', "Motivo de Devolução de Cheque '{$model->chequemotivodevolucao}' atualizado!");

        return redirect("cheque-motivo-devolucao/$model->codchequemotivodevolucao");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
