<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use MGLara\Models\ProdutoBarra;
use MGLara\Models\EstoqueSaldoConferencia;
use MGLara\Models\EstoqueSaldo;

class EstoqueSaldoConferenciaController extends Controller
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
        $data = $request->data;
        
        $this->converteDatas(['data' => $request->input('data')]);
        
        $model = new EstoqueSaldoConferencia($request->all());
        
        $codestoquelocal = $request->codestoquelocal;
        
        $pb = null;
        $barras = $request->get('barras');
        if (!empty($barras))
            if (!($pb = ProdutoBarra::buscaPorBarras($barras)))
                Session::flash('flash_danger', "Código de barras '{$barras}' não localizado!");
        
        $fiscal = $request->get('fiscal');
        
        if ($pb && !empty($codestoquelocal) && !empty($model->data))
        {
            $es = EstoqueSaldo::buscaOuCria($pb->codproduto, $codestoquelocal, (!empty($request->get('fiscal'))));
            $model->codestoquesaldo = $es->codestoquesaldo;
            $model->quantidadeinformada = $es->saldoquantidade;
            $model->customedioinformado = $es->customedio;
        }
        
        return view('estoque-saldo-conferencia.create', compact('model', 'pb', 'barras', 'data', 'codestoquelocal', 'fiscal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->converteNumericos([
            'estoquemaximo' => $request->input('estoquemaximo'),
            'estoqueminimo' => $request->input('estoqueminimo'),
            'corredor' => $request->input('corredor'),
            'prateleira' => $request->input('prateleira'),
            'coluna' => $request->input('coluna'),
            'bloco' => $request->input('bloco'),
            'quantidadeinformada' => $request->input('quantidadeinformada'),
            'customedioinformado' => $request->input('customedioinformado'),
        ]);
        
        //$this->converteDatas(['data' => $request->input('data')]);
        
        $es = EstoqueSaldo::findOrFail($request->get('codestoquesaldo'));
        
        $es->EstoqueLocalProduto->estoquemaximo = $request->get('estoquemaximo');
        $es->EstoqueLocalProduto->estoqueminimo = $request->get('estoqueminimo');
        $es->EstoqueLocalProduto->corredor = $request->get('corredor');
        $es->EstoqueLocalProduto->prateleira = $request->get('prateleira');
        $es->EstoqueLocalProduto->coluna = $request->get('coluna');
        $es->EstoqueLocalProduto->bloco = $request->get('bloco');
        
        $es->EstoqueLocalProduto->save();
        
        $model = new EstoqueSaldoConferencia();
        
        $model->codestoquesaldo = $es->codestoquesaldo;
        $model->quantidadesistema = $es->saldoquantidade;
        $model->quantidadeinformada = $request->get('quantidadeinformada');
        $model->customediosistema = $es->customedio;
        $model->customedioinformado = $request->get('customedioinformado');
        $model->data = $request->get('data');
        
        $model = new EstoqueSaldoConferencia($request->all());
        
        $model->save();
        Session::flash('flash_success', 'Salvo com sucesso!');
        $data = $model->data->format('d/m/Y H:i:s');
        $fiscal = $es->fiscal;
        $codestoquelocal = $es->EstoqueLocalProduto->codestoquelocal;
        return redirect("estoque-saldo-conferencia/create?data=$data&fiscal=$fiscal&codestoquelocal=$codestoquelocal");
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
