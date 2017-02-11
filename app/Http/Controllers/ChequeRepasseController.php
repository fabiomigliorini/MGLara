<?php

namespace MGLara\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\ChequeRepasse;
use MGLara\Models\Cheque;
class ChequeRepasseController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $parametros = self::filtroEstatico($request, 'cheque-repasse.index', []);
        $model = ChequeRepasse::search($parametros)
            ->orderBy('criacao', 'desc')
            ->paginate(20);
        return view('cheque-repasse.index', compact('model'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $model = new ChequeRepasse();
        return view('cheque-repasse.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        /*
        $dados = $request->all();
        $model = new ChequeMotivoDevolucao($dados);

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        $model->save();
        Session::flash('flash_success', "Motivo de Devolução de Cheque '{$model->chequemotivodevolucao}' criado!");

        return redirect("cheque-motivo-devolucao/$model->codchequemotivodevolucao");
         *
         */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

       //$model = ChequeMotivoDevolucao::findOrFail($id);
        //return view('cheque-motivo-devolucao.show', compact('model'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        /*
        $model = ChequeMotivoDevolucao::findOrFail($id);

        return view('cheque-motivo-devolucao.edit',  compact('model'));
         *
         */
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        /*
        $dados = $request->all();
        $model = ChequeMotivoDevolucao::findOrFail($id);
        $model->fill($dados);
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }


        $model->save();
        Session::flash('flash_success', "Motivo de Devolução de Cheque '{$model->chequemotivodevolucao}' atualizado!");

        return redirect("cheque-motivo-devolucao/$model->codchequemotivodevolucao");
        */
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

    public function consulta(Request $request) {

        $parametros = $request->all();
        $indstatus_descricao = ['' => ''] + Cheque::$indstatus_descricao;
        $indstatus_class = Cheque::$indstatus_class;

        $query = Cheque::query();
        $pass=0;
        if (!empty($parametros['vencimento_de'])){
            $query->where('vencimento','>=', $parametros['vencimento_de']);
            $pass=1;
        }
        if (!empty($parametros['vencimento_ate'])){
            $query->where('vencimento','<=', $parametros['vencimento_ate']);
            $pass=1;
        }

        if($pass==1){
            $cheques = $query->orderBy('vencimento', 'asc')->get();

            $ret = [];
            foreach($cheques as $cheque){

                $status = "<span class='label ".$indstatus_class[$cheque->indstatus]."'>".$indstatus_descricao[$cheque->indstatus]."</span>";
                $emitentes = null;
                foreach($cheque->ChequeEmitenteS as $emit){
                    $emitentes .= $emit->emitente.'<br>';
                }

                $ret[] = [
                   'linkcheque' => url('cheque', $cheque->codcheque),
                   'codcheque' => $cheque->codcheque,
                   'agencia' => $cheque->agencia,
                   'contacorrente' => $cheque->contacorrente,
                   'numero' => formataNumero($cheque->numero, 0),
                   'banco' => $cheque->Banco->banco,
                   'pessoa' => $cheque->Pessoa->pessoa,
                   'linkpessoa' => url('pessoa', $cheque->codpessoa),
                   'emitentes' => $emitentes,
                   'valor' => formataNumero($cheque->valor),
                   'emissao' => formataData($cheque->emissao),
                   'vencimento' => formataData($cheque->vencimento),
                   'status' => $status,
                ];
            }

            return [
                'status' => true,
                'cheques' => $ret
            ];

        }else{
            return [
                'status' => false,
                'error' => 'Preencha os campos corretamente'
            ];
        }
    }

}
