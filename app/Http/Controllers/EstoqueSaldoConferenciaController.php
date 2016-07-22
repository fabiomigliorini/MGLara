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
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['index']]);
    }     
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('estoque-saldo-conferencia.index')) 
            $request->session()->put('estoque-saldo-conferencia.index');
        
        $parametros = $request->session()->get('estoque-saldo-conferencia.index');
        
        if (!empty($parametros['data_de']))
            $parametros['data_de'] = new Carbon($parametros['data_de'] . ' 00:00:00');
        if (!empty($parametros['data_ate']))
            $parametros['data_ate'] = new Carbon($parametros['data_ate'] . ' 23:59:59');
        
        if (!empty($parametros['criacao_de']))
            $parametros['criacao_de'] = new Carbon($parametros['criacao_de'] . ' 00:00:00');
        if (!empty($parametros['criacao_ate']))
            $parametros['criacao_ate'] = new Carbon($parametros['criacao_ate'] . ' 23:59:59');
        
        //dd($parametros['ajuste_ate']);
        
        $model = EstoqueSaldoConferencia::search($parametros)->orderBy('codestoquesaldoconferencia', 'DESC')->paginate(20);
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
        
        if ($request->has('codestoquesaldo')) {
            
            $codestoquesaldo = $request->codestoquesaldo;
            
            $es = EstoqueSaldo::findOrFail($codestoquesaldo);
            
            $codestoquelocal = $es->EstoqueLocalProdutoVariacao->codestoquelocal;
            $codprodutovariacao = $es->EstoqueLocalProdutoVariacao->codprodutovariacao;
            
            $pv = $es->EstoqueLocalProdutoVariacao->ProdutoVariacao;
            
            $estoque = $pv->Produto->getArraySaldoEstoque();
            
            
            $fiscal = $es->fiscal;
            
            $elpv = $es->EstoqueLocalProdutoVariacao;
            
            $corredor = $elpv->corredor;
            $prateleira = $elpv->prateleira;
            $coluna = $elpv->coluna;
            $bloco = $elpv->bloco;
            $estoqueminimo =  $elpv->estoqueminimo;
            $estoquemaximo = $elpv->estoquemaximo;
            
            $customedio = $es->customedio;
            $quantidadeinformada = $es->saldoquantidade;
            
            $view = 'estoque-saldo-conferencia.create';
            
        } else {

            if ($request->has('codestoquelocal')) {
                $request->session()->put('codestoquelocal', $request->codestoquelocal);
            }
            $codestoquelocal = $request->session()->get('codestoquelocal', null);
            
            if (!empty($codestoquelocal)) {
                $el = EstoqueLocal::findOrFail($codestoquelocal);
            }

            if ($request->has('data')) {
                $request->session()->put('data', $request->data);
            }
            $data = $request->session()->get('data', null);

            if ($request->has('fiscal')) {
                $request->session()->put('fiscal', $request->fiscal);
            }
            $fiscal = (boolean) $request->session()->get('fiscal', null);


            $codprodutovariacao = $request->codprodutovariacao;
        
            $barras = $request->barras;
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
