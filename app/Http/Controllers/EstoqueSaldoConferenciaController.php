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

use Illuminate\Support\Facades\DB;

use MGLara\Jobs\EstoqueGeraMovimentoConferencia;
use MGLara\Jobs\EstoqueCalculaCustoMedio;

class EstoqueSaldoConferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = self::filtroEstatico(
            $request, 
            'estoque-saldo-conferencia.index', 
            [], 
            ['criacao_de', 'criacao_ate', 'data_de', 'data_ate']
        );
        $model = EstoqueSaldoConferencia::search($parametros)->select('tblestoquesaldoconferencia.*')->orderBy('codestoquesaldoconferencia', 'DESC')->paginate(20);
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
        
        if ($quantidadeinformada == 0) {
            $quantidadeinformada = null;
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
        try{
            
            DB::beginTransaction();
            
            $model = EstoqueSaldoConferencia::findOrFail($id);
            
            $codestoquemesRecalcular = [];
            
            foreach($model->EstoqueMovimentoS as $filho) {
                $codestoquemesRecalcular[] = $filho->codestoquemes;
                $filho->delete();
            }
            
            $model->delete();
            
            $model->EstoqueSaldo->ultimaconferencia = $model->EstoqueSaldo->EstoqueSaldoConferenciaS()->max('criacao');
            $model->EstoqueSaldo->save();
            
            DB::commit();
            
            foreach ($codestoquemesRecalcular as $cod) {
                $this->dispatch((new EstoqueCalculaCustoMedio($cod))->onQueue('urgent'));
            }
            
            $ret = ['resultado' => true, 'mensagem' => 'Conferência de Saldo excluída com sucesso!'];
        }
        catch(\Exception $e){
            DB::rollBack();
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir Conferência de Saldo!', 'exception' => $e];
        }
        return json_encode($ret);
    }
}
