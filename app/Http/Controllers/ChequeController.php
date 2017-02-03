<?php

namespace MGLara\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Cheque;
use MGLara\Models\ChequeEmitente;
use MGLara\Models\Banco;
use MGLara\Models\Pessoa;
use MGLara\Library\Cmc7\Cmc7;
class ChequeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $parametros = self::filtroEstatico($request, 'cheque.index', []);
        //dd($parametros);
        $model = Cheque::search($parametros)
            ->orderBy('vencimento', 'DESC')
            ->orderBy('valor', 'ASC')
            ->orderBy('criacao', 'DESC')
            ->paginate(20);

        $indstatus_descricao = ['' => ''] + Cheque::$indstatus_descricao;
        $indstatus_class = Cheque::$indstatus_class;

        return view('cheque.index', compact('model', 'indstatus_descricao', 'indstatus_class'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $model = new Cheque();
        return view('cheque.create', compact('model','request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        DB::beginTransaction();

        $dados = $request->all();
        $model = new Cheque($dados);
        $model->parseCmc7();
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        $model->save();

        $cnpjs = $request->chequeemitente_cnpj;
        $emitentes = $request->chequeemitente_emitente;

        foreach ($cnpjs as $i => $cnpj) {
            $emit = new ChequeEmitente();
            $emit->codcheque = $model->codcheque;
            $emit->cnpj = $cnpj;
            $emit->emitente = $emitentes[$i];
            $emit->save();
        }

        DB::commit();

        Session::flash('flash_success', "Cheque '{$model->cmc7}' criado!");

        return redirect("cheque/create");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $model = Cheque::findOrFail($id);
        return view('cheque.show', compact('model'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $model = Cheque::findOrFail($id);
        return view('cheque.edit', compact('model'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        DB::beginTransaction();

        $dados = $request->all();
        $model = Cheque::findOrFail($id);
        $model->fill($dados);
        $model->parseCmc7();
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        $model->save();

        $cnpjs = $request->chequeemitente_cnpj;
        $emitentes = $request->chequeemitente_emitente;
        $codchequeemitentes = $request->chequeemitente_codchequeemitente;

        $codchequeemitentes_salvos = [];
        foreach ($cnpjs as $i => $cnpj) {
            if(empty($codchequeemitentes[$i])){
                $emit = new ChequeEmitente();
            }else{
                $emit = ChequeEmitente::findOrFail($codchequeemitentes[$i]);
            }
            $emit->codcheque = $model->codcheque;
            $emit->cnpj = $cnpj;
            $emit->emitente = $emitentes[$i];
            $emit->save();
            $codchequeemitentes_salvos[] = $emit->codchequeemitente;
        }
        ChequeEmitente::where('codcheque','=',$model->codcheque)->whereNotIn('codchequeemitente',$codchequeemitentes_salvos)->delete();

        DB::commit();

        Session::flash('flash_success', "Cheque '{$model->cmc7}' Salvo!");

        return redirect("cheque/$model->codcheque");

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


    public function consulta($cmc7) {

        if($consultacmc7 = Cheque::where('cmc7','=',$cmc7)->first()){
           return [
            'valido' => false,
            'error' => 'Já existe um cadastro com esse CMC7. #'.$consultacmc7->codcheque
            ];
           exit;
        }

        $cmc7n = new Cmc7($cmc7);

        //dd($cmc7n->banco());
        $ultimo = [
            'codpessoa' => null,
            'emitentes' => [],
        ];
        //------- Pesquisa se há emitentes para o cheque cadastrado
        if ($retorno = Cheque::findUltimoMesmoEmitente($cmc7n->banco(), $cmc7n->agencia(), $cmc7n->contacorrente())) {

            $ultimo['codpessoa'] = $retorno->codpessoa;

            foreach ($retorno->ChequeEmitenteS as $emit) {
                $ultimo['emitentes'][] = [
                    'cnpj' => $emit->cnpj,
                    'emitente' => $emit->emitente,
                ];
                if($ultimo['codpessoa']== null){
                    if($pessoa = Pessoa::where('cnpj', $emit->cnpj)->first()){
                        $ultimo['codpessoa'] = $pessoa['codpessoa'];
                    }
                }
            }
        }
        //------- Consulta Banco
        if($banco = Banco::where('numerobanco', '=', $cmc7n->banco())->first()){
            $banco_nome = $banco->banco;
        }else{
            $banco_nome = $cmc7n->banco();
        }
        //------ Consultar pelo emitente
        if($cmc7n->valido()==false){
            $error = 'CMC7 Inválido';
        }else{
            $error = null;
        }
        return [
            'valido' => $cmc7n->valido(),
            'error' => $error,
            'banco' => $banco_nome,
            'agencia' => $cmc7n->agencia(),
            'contacorrente' => $cmc7n->contacorrente(),
            'numero' => $cmc7n->numero(),
            'ultimo' => $ultimo,
        ];


    }

    public function consultaemitente($cnpj) {

        $retorno = [
            'codpessoa' => null,
            'pessoa' => null,
        ];
        if($pessoa = Pessoa::where('cnpj', $cnpj)->first()){
            $retorno['codpessoa'] = $pessoa->codpessoa;
            $retorno['pessoa'] = $pessoa->pessoa;
        }
        return $retorno;
    }
}
