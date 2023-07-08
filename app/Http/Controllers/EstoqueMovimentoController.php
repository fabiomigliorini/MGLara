<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;

use MGLara\Jobs\EstoqueCalculaCustoMedio;

use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueLocal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class EstoqueMovimentoController extends Controller
{
    
    public function __construct()
    {
        $this->datas = [];
        $this->numericos = [];
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $codestoquemes)
    {
        $model = new EstoqueMovimento();
        $model->codestoquemes = $codestoquemes;
        $model->data = $model->EstoqueMes->mes->endOfMonth();
        $model->codestoquemovimentotipo = 1002;
	
        $sql = '
            select max(saldoquantidade) as max, min(saldoquantidade) as min 
            from tblestoquemes em 
            where em.codestoquesaldo = :codestoquesaldo 
            and em.mes >= :mes
        ';
        //dd($model->EstoqueMes->codestoquesaldo);

        //dd($model->mes);
        $minmax = DB::select($sql, [
            'codestoquesaldo' => $model->EstoqueMes->codestoquesaldo,
            'mes' => $model->EstoqueMes->mes->format('Y-m-d')
        ]);
        $min = doubleval($minmax[0]->min);
        $max = doubleval($minmax[0]->max);

        if ($min < 0) {
            $model->entradaquantidade = abs($min);
            $model->entradaunitario = abs($model->EstoqueMes->customedio);
            $model->entradavalor = round($model->entradaquantidade * $model->entradaunitario, 2);
        } elseif ($min > 0) {
            $model->saidaquantidade = abs($min);
            $model->saidaunitario = abs($model->EstoqueMes->customedio);
            $model->saidavalor = round($model->saidaquantidade * $model->saidaunitario, 2);
        }

        /*
        dd($model);

        if ($model->EstoqueMes->saldoquantidade < 0) {
        $model->entradaquantidade = abs($model->EstoqueMes->saldoquantidade);
        $model->entradavalor = abs($model->EstoqueMes->saldovalor);
        $model->entradaunitario = $model->entradavalor / $model->entradaquantidade;
        }
        if ($model->EstoqueMes->saldoquantidade > 0) {
             $model->saidaquantidade = abs($model->EstoqueMes->saldoquantidade);
             $model->saidavalor = abs($model->EstoqueMes->saldovalor);
             $model->saidaunitario = $model->saidavalor / $model->saidaquantidade;
        }
        */	


        
        $codprodutoorigem = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto;
        $codprodutovariacaoorigem = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao;
        $codestoquelocalorigem = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal;
        
        $tipoPrecoInformado = EstoqueMovimentoTipo::where('preco', EstoqueMovimentoTipo::PRECO_INFORMADO)->lists('codestoquemovimentotipo');
        $tipoOrigem = EstoqueMovimentoTipo::whereNotNull('codestoquemovimentotipoorigem')->lists('codestoquemovimentotipo');
        
        return view('estoque-movimento.create',  compact('model', 'tipoPrecoInformado', 'tipoOrigem', 'codprodutoorigem', 'codprodutovariacaoorigem', 'codestoquelocalorigem'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new EstoqueMovimento();
        $model->manual = true;
        return $this->salva($request, $model);
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
        
        if (isset($model->EstoqueMovimentoS[0])) {
            return redirect("estoque-movimento/{$model->EstoqueMovimentoS[0]->codestoquemovimento}/edit");
        }
        
        $codprodutoorigem = 
                empty($model->codestoquemovimentoorigem)
                ?$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto
                :$model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto;
        
        $codprodutovariacaoorigem = 
                empty($model->codestoquemovimentoorigem)
                ?$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao
                :$model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao;
        
        $codestoquelocalorigem = 
                empty($model->codestoquemovimentoorigem)
                ?$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal
                :$model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal;
        
        $tipoPrecoInformado = EstoqueMovimentoTipo::where('preco', EstoqueMovimentoTipo::PRECO_INFORMADO)->lists('codestoquemovimentotipo');
        $tipoOrigem = EstoqueMovimentoTipo::whereNotNull('codestoquemovimentotipoorigem')->lists('codestoquemovimentotipo');
        
        return view('estoque-movimento.edit',  compact('model', 'tipoPrecoInformado', 'tipoOrigem', 'codprodutoorigem', 'codprodutovariacaoorigem', 'codestoquelocalorigem'));
    }
    
    public function salva(Request $request, EstoqueMovimento $model)
    {
        
        $dados = $request->all();
        $dados['data'] = new Carbon($dados['data']);
        
        $model->fill($dados);
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        
        if (!$model->manual) {
            $this->throwValidationException('Registro gerado automaticamente pelo sistema, não pode ser atualizado manualmente!');
        }
        
        
        DB::beginTransaction();
        
        $codestoquemesRecalcular = [];
        
        try {
            
            //Cria registro de Origem
            $codestoquemovimentotipoorigem = $model->EstoqueMovimentoTipo->codestoquemovimentotipoorigem;
            if (!empty($codestoquemovimentotipoorigem))
            {
                if (!empty($model->codestoquemovimentoorigem)) {
                    $origem = $model->EstoqueMovimentoOrigem;
                } else {
                    $origem = new EstoqueMovimento;
                }

                $emOrigem = EstoqueMes::buscaOuCria(
                        $request->input('codprodutovariacaoorigem'), 
                        $request->input('codestoquelocalorigem'), 
                        $model->EstoqueMes->EstoqueSaldo->fiscal, 
                        $model->data);
                
                if (!empty($origem->codestoquemes) && $origem->codestoquemes != $emOrigem->codestoquemes) {
                    $codestoquemesRecalcular[] = $origem->codestoquemes;
                }

                $origem->codestoquemes = $emOrigem->codestoquemes;
                $codestoquemesRecalcular[] = $origem->codestoquemes;
                
                $origem->codestoquemovimentotipo = $model->EstoqueMovimentoTipo->codestoquemovimentotipoorigem;
                $origem->data = $model->data;
                $origem->entradaquantidade = $model->saidaquantidade;
                $origem->entradavalor = $model->saidavalor;
                $origem->saidaquantidade = $model->entradaquantidade;
                $origem->saidavalor = $model->entradavalor ; 
                $origem->manual = true;
                
                if (!$origem->save()) {
                    throw new Exception('Erro ao Salvar Movimento de Origem!');
                }

                $origem = EstoqueMovimento::find($origem->codestoquemovimento);
                
                $model->codestoquemovimentoorigem = $origem->codestoquemovimento;
            }
            
            $em = EstoqueMes::buscaOuCria(
                    $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao,
                    $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal,
                    $model->EstoqueMes->EstoqueSaldo->fiscal, 
                    $model->data);
            
            if (!empty($model->codestoquemes) && $model->codestoquemes != $em->codestoquemes) {
                $codestoquemesRecalcular[] = $model->codestoquemes;
            }
            $model->codestoquemes = $em->codestoquemes;
            
            $codestoquemesRecalcular[] = $model->codestoquemes;

            if (!$model->save()) {
                throw new Exception('Erro ao Salvar Movimento!');
            }
            
            DB::commit();
            
            foreach ($codestoquemesRecalcular as $cod) {
                $this->dispatch((new EstoqueCalculaCustoMedio($cod))->onQueue('urgent'));
            }
            
            Session::flash('flash_success', 'Registro Atualizado!');
            
        } catch (Exception $ex) {
            DB::rollBack();
            Session::flash('flash_error', "Erro ao salvar registro! {$ex}");
        }
        
        return redirect("estoque-mes/$model->codestoquemes");
        
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
        
        return $this->salva($request, $model);
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
            
            $model = EstoqueMovimento::findOrFail($id);
            
            if (!empty($model->codestoquemovimentoorigem)) {
                $model = $model->EstoqueMovimentoOrigem;
            }
            
            foreach($model->EstoqueMovimentoS as $filho) {
                $codestoquemesRecalcular[] = $filho->codestoquemes;
                $filho->delete();
            }
            
            $codestoquemesRecalcular[] = $model->codestoquemes;
            $model->delete();
            
            DB::commit();
            
            foreach ($codestoquemesRecalcular as $cod) {
                $this->dispatch((new EstoqueCalculaCustoMedio($cod))->onQueue('urgent'));
            }
            
            $ret = ['resultado' => true, 'mensagem' => 'Movimento excluído com sucesso!'];
        }
        catch(\Exception $e){
            DB::rollBack();
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir Movimento!', 'exception' => $e];
        }
        return json_encode($ret);
    }
}
