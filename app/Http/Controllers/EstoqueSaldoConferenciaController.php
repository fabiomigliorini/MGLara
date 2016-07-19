<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use MGLara\Models\ProdutoBarra;
use MGLara\Models\EstoqueSaldoConferencia;
use MGLara\Models\EstoqueSaldo;
use Carbon\Carbon;

use MGLara\Models\EstoqueLocal;
use MGLara\Models\EstoqueLocalProdutoVariacao;
use MGLara\Models\ProdutoVariacao;

use MGLara\Jobs\EstoqueGeraMovimentoConferencia;

class EstoqueSaldoConferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = $request;
        $model = EstoqueSaldoConferencia::search($parametros);
        return view('estoque-saldo-conferencia.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $codestoquelocal = null;
        $fiscal = null;
        $data = null;
        $corredor = null;
        $prateleira = null;
        $coluna = null;
        $bloco = null;
        $estoqueminimo = null;
        $estoquemaximo = null;
        $customedio = null;
        $quantidadeinformada = null;
        $pv = null;
        $elpv = null;
        $es = null;
        $view = 'estoque-saldo-conferencia.create-seleciona';
        
        if ($request->has('codestoquelocal')) {
            $request->session()->put('codestoquelocal', $request->codestoquelocal);
        }
        $codestoquelocal = $request->session()->get('codestoquelocal', null);
        
        if ($request->has('data')) {
            $request->session()->put('data', $request->data);
        }
        $data = $request->session()->get('data', null);
        
        if ($request->has('fiscal')) {
            $request->session()->put('fiscal', $request->fiscal);
        }
        $fiscal = (boolean) $request->session()->get('fiscal', null);
            
        $barras = $request->barras;
        
        $codprodutovariacao = $request->codprodutovariacao;
        
        if (!empty($codestoquelocal)) {
            $el = EstoqueLocal::findOrFail($codestoquelocal);
        }
        
        if (!empty($barras)) {
            if (!($pb = ProdutoBarra::buscaPorBarras($barras))) {
                Session::flash('flash_danger', "Código de barras '{$barras}' não localizado!");
            } else {
                $codprodutovariacao = $pb->codprodutovariacao;
            }
        }
        
        if (!empty($codprodutovariacao)) {
            
            $view = 'estoque-saldo-conferencia.create';
            
            $pv = ProdutoVariacao::findOrFail($codprodutovariacao);
            $estoque = $pv->Produto->getArraySaldoEstoque();
            
            if ($elp = $pv->EstoqueLocalProdutoVariacaoS()->where('codestoquelocal', $codestoquelocal)->first()) {
                
                $corredor = $elp->corredor;
                $prateleira = $elp->prateleira;
                $coluna = $elp->coluna;
                $bloco = $elp->bloco;
                $estoqueminimo = $elp->estoqueminimo;
                $estoquemaximo = $elp->estoquemaximo;
                
                if ($es = $elp->EstoqueSaldoS()->where('fiscal', (bool) $fiscal)->first()) {
                    
                    $customedio = $es->customedio;
                    $quantidadeinformada = $es->saldoquantidade;
                    
                }
                
            }
            
            
        }
        
        return view($view, compact(
                'data', 
                'codestoquelocal', 
                'codprodutovariacao', 
                'barras', 
                'fiscal', 
                'corredor', 
                'prateleira', 
                'coluna', 
                'bloco',
                'estoque',
                'estoqueminimo',
                'estoquemaximo',
                'customedio',
                'quantidadeinformada',
                'pv',
                'elpv',
                'es'
            ));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $es = EstoqueSaldo::buscaOuCria($request->codprodutovariacao, $request->codestoquelocal, (boolean) $request->fiscal);
        
        $es->EstoqueLocalProdutoVariacao->estoquemaximo = $request->estoquemaximo;
        $es->EstoqueLocalProdutoVariacao->estoqueminimo = $request->estoqueminimo;
        $es->EstoqueLocalProdutoVariacao->corredor = $request->corredor;
        $es->EstoqueLocalProdutoVariacao->prateleira = $request->prateleira;
        $es->EstoqueLocalProdutoVariacao->coluna = $request->coluna;
        $es->EstoqueLocalProdutoVariacao->bloco = $request->bloco;
        
        $es->EstoqueLocalProdutoVariacao->save();
        
        $model = new EstoqueSaldoConferencia();
        
        $model->codestoquesaldo = $es->codestoquesaldo;
        $model->quantidadesistema = $es->saldoquantidade;
        $model->quantidadeinformada = $request->quantidadeinformada;
        $model->customediosistema = $es->customedio;
        $model->customedioinformado = $request->customedioinformado;
        
        $model->data = new Carbon($request->data);
        $request->session()->put('data', $request->data);
        
        $model->save();
        
        $model->EstoqueSaldo->ultimaconferencia = $model->criacao;
        $model->EstoqueSaldo->save();

        $this->dispatch((new EstoqueGeraMovimentoConferencia($model->codestoquesaldoconferencia))->onQueue('urgent'));
        
        Session::flash('flash_success', 'Salvo com sucesso!');
        
        $data = $model->data->format('d/m/Y H:i:s');
        $fiscal = $es->fiscal;
        $codestoquelocal = $es->EstoqueLocalProdutoVariacao->codestoquelocal;
        return redirect("estoque-saldo-conferencia/create");
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
