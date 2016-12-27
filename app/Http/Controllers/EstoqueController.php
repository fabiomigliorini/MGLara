<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use MGLara\Jobs\EstoqueCalculaCustoMedio;
use MGLara\Jobs\EstoqueGeraMovimentoNegocioProdutoBarra;
use MGLara\Jobs\EstoqueGeraMovimentoNegocio;
use MGLara\Jobs\EstoqueGeraMovimentoNotaFiscalProdutoBarra;
use MGLara\Jobs\EstoqueGeraMovimentoNotaFiscal;
use MGLara\Jobs\EstoqueGeraMovimentoProduto;
use MGLara\Jobs\EstoqueGeraMovimentoProdutoVariacao;
use MGLara\Jobs\EstoqueGeraMovimentoPeriodo;
use MGLara\Jobs\EstoqueGeraMovimentoConferencia;

use MGLara\Models\EstoqueMes;
use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\Negocio;
use MGLara\Models\Produto;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueSaldoConferencia;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;

class EstoqueController extends Controller
{
    
    /**
     * Calcula Custo Médio do Estoque
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function calculaCustoMedio(Request $request, $id)
    {
        $this->dispatch((new EstoqueCalculaCustoMedio($id))->onQueue('urgent'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoNegocioProdutoBarra(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoNegocioProdutoBarra($id))->onQueue('high'));
        return response()->json(['response' => 'Agendado']);
    }

    public function geraMovimentoNegocio(Request $request, $id)
    {
        //Delay pra aguardar transação do Yii
        $delay = (int)$request->get('delay');
        $job = (new EstoqueGeraMovimentoNegocio($id))->delay($delay)->onQueue('medium');
        $this->dispatch($job);
        return response()->json(['response' => 'Agendado', 'delay' => $delay]);
    }
    
    public function geraMovimentoNotaFiscalProdutoBarra(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoNotaFiscalProdutoBarra($id))->onQueue('high'));
        return response()->json(['response' => 'Agendado']);
    }

    public function geraMovimentoNotaFiscal(Request $request, $id)
    {
        //Delay pra aguardar transação do Yii
        $delay = (int)$request->get('delay');
        $job = (new EstoqueGeraMovimentoNotaFiscal($id))->delay($delay)->onQueue('medium');
        $this->dispatch($job);
        return response()->json(['response' => 'Agendado', 'delay' => $delay]);
    }
    
    public function geraMovimentoProduto(Request $request, $id)
    {
        $fisico = ($request->get('fisico') === 'false')?false:true;
        $fiscal = ($request->get('fiscal') === 'false')?false:true;
        
        $this->dispatch((new EstoqueGeraMovimentoProduto($id, $fisico, $fiscal))->onQueue('low'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoProdutoVariacao(Request $request, $id)
    {
        $fisico = ($request->get('fisico') === 'false')?false:true;
        $fiscal = ($request->get('fiscal') === 'false')?false:true;
        
        $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($id, $fisico, $fiscal))->onQueue('low'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoPeriodo(Request $request, $inicial, $final)
    {
        $inicial = new Carbon($inicial);
        $final = new Carbon($final);
        
        $fisico = ($request->get('fisico') === 'false')?false:true;
        $fiscal = ($request->get('fiscal') === 'false')?false:true;
        
        $this->dispatch((new EstoqueGeraMovimentoPeriodo($inicial, $final, $fisico, $fiscal))->onQueue('low'));
        
        return response()->json(['response' => 'Agendado']);
    }
    
/*
    public function geraSaldoConferenciaNegocio(Request $request, $id)
    {
        $obs = "Criado a partir do Negocio " . formataCodigo($id);
        
        if ($esc = EstoqueSaldoConferencia::where('observacoes', $obs)->first())
            die('Já foi criada conferencia de estoque deste negocio!');
        
        try {
            
            $sql = "select  
                            pb.codprodutovariacao
                          , n.codestoquelocal
                          , sum(npb.quantidade) as quantidadeinformada
                          , min(npb.alteracao) as alteracao
                          , min(npb.codusuarioalteracao) as codusuarioalteracao
                          , min(npb.criacao) as criacao
                          , min(npb.codusuariocriacao) as codusuariocriacao
                  from tblnegocioprodutobarra npb
                  left join tblnegocio n on (n.codnegocio = npb.codnegocio)
                  left join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
                  where npb.codnegocio = $id
                  group by 
                            pb.codprodutovariacao
                          , n.codestoquelocal

                  ";

            $regs = DB::select($sql);

            DB::beginTransaction();
            $gerados = [];
            
            foreach ($regs as $reg)
            {
                $es = EstoqueSaldo::buscaOuCria($reg->codprodutovariacao, $reg->codestoquelocal, false);

                $model = new EstoqueSaldoConferencia();

                $model->codestoquesaldo = $es->codestoquesaldo;
                $model->quantidadesistema = $es->saldoquantidade;
                //$model->quantidadeinformada = $es->saldoquantidade + $reg->quantidadeinformada;
                $model->quantidadeinformada = $reg->quantidadeinformada;
		if ($model->quantidadesistema > 0) {
                    $model->quantidadeinformada += $es->saldoquantidade;
		} else {
                    //dd($model);
                }
                $model->customediosistema = $es->customedio;

                $model->customedioinformado = $es->customedio;
                
                // Tenta custo Medio pela ultima compra do estoque
                if (empty($model->customedioinformado))
                {
                    $sql = "select em.entradavalor / em.entradaquantidade as custo
                            from tblestoquemovimento em
                            inner join tblestoquemes mes on (mes.codestoquemes = em.codestoquemes)
                            inner join tblestoquesaldo es on (es.codestoquesaldo = mes.codestoquesaldo and es.fiscal = false)
                            inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
                            inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
                            where em.codestoquemovimentotipo = 2001 -- COMPRA
                            and pv.codproduto = {$es->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                            and coalesce(em.entradaquantidade, 0) > 0
                            order by data desc
                            limit 1
                        ";

                    if ($custo = DB::select($sql))
                        $model->customedioinformado = $custo[0]->custo;
                }
                
                //Tenta pela media do custo medio fisico
                if (empty($model->customedioinformado))
                {
                    $sql = "select avg(es.customedio) as custo
                            from tblprodutovariacao pv
                            inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                            inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false)
                            where pv.codproduto = {$es->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                            and es.customedio is not null
                            and es.customedio > 0
                        ";
                            
                    if ($custo = DB::select($sql))
                        $model->customedioinformado = $custo[0]->custo;
                }

                //Tenta pela media do custo medio fisico
                if (empty($model->customedioinformado))
                {
                    $sql = "select avg(es.customedio) as custo
                            from tblprodutovariacao pv
                            inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                            inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                            where pv.codproduto = {$es->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                            and es.customedio is not null
                            and es.customedio > 0
                        ";

                    if ($custo = DB::select($sql))
                        $model->customedioinformado = $custo[0]->custo;
                }
                
                //Tenta pela ultima nota de compra
                if (empty($model->customedioinformado))
                {
                    $sql = "select (((nfpb.valortotal + nfpb.icmsstvalor + nfpb.ipivalor) * (1- (nf.valordesconto / nf.valorprodutos))) / nfpb.quantidade) / coalesce(pe.quantidade, 1) as custo, nf.codnotafiscal
                            from tblnotafiscal nf
                            inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscal = nf.codnotafiscal)
                            inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
                            inner join tblprodutovariacao pv on (pv.codprodutovariacao = pb.codprodutovariacao)
                            left join tblprodutoembalagem pe on (pe.codprodutoembalagem = pb.codprodutoembalagem)
                            where nf.codnaturezaoperacao = 4
                            and pv.codproduto = {$es->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                            order by nf.saida desc
                            limit 1
                        ";
                            
                    if ($custo = DB::select($sql))
                        $model->customedioinformado = $custo[0]->custo;
                }
                
                if (empty($model->customedioinformado))
                    $model->customedioinformado = $es->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->preco * 0.571428571; // 75% markup
                
                $model->data = new Carbon();
                $model->observacoes = $obs;

                if (!$model->save())
                    throw new Exception ('Erro ao Salvar EstoqueSaldoConferencia');

                $model->EstoqueSaldo->ultimaconferencia = $model->criacao;
                if (!$model->EstoqueSaldo->save())
                    throw new Exception ('Erro ao Salvar EstoqueSaldo');
                
                $gerados[] = [
                    'codestoquesaldoconferencia' => $model->codestoquesaldoconferencia,
                    'codestoquesaldo' => $model->codestoquesaldo,
                    'codproduto' => $es->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto,
                    'codprodutovariacao' => $es->EstoqueLocalProdutoVariacao->codprodutovariacao,
                    'quantidadeinformada' => $model->quantidadeinformada,
                    'customedio' => $model->customedioinformado,
                    'codestoquelocal' => $es->EstoqueLocalProdutoVariacao->codestoquelocal,
                        ];

            }

            //die('antes commit');
            
            DB::commit();
            
            foreach ($gerados as $reg)
            {
                $this->dispatch((new EstoqueGeraMovimentoConferencia($reg["codestoquesaldoconferencia"]))->onQueue('urgent'));
            }
            
            return response()->json(['response' => 'Gerado', 'registros' => $gerados]);
            
        } catch (Exception $exc) {
            DB:rollback();
            dd($exc);
        }
    }
*/

/*
    
    public function zeraSaldo ($id, $tipo)
    {
        
        DB::enableQueryLog();
        
        switch ($tipo) {
            case 'todos':
                $sldquantidade = 'es.saldoquantidade <> 0';
                break;

            case 'positivo':
                $sldquantidade = 'es.saldoquantidade > 0';
                break;

            case 'negativo':
            default:
                $sldquantidade = 'es.saldoquantidade > 0';
                break;
        }
        
        $sql = "
            select es.codestoquesaldo, elpv.codestoquelocal, elpv.codprodutovariacao, es.fiscal, pv.codproduto, es.saldoquantidade
            from tblestoquesaldo es
            inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
            inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
            where $sldquantidade
            and es.fiscal = false
            and elpv.codestoquelocal = $id
            and es.codestoquesaldo not in (select esc.codestoquesaldo from tblestoquesaldoconferencia esc)
            order by pv.codproduto, pv.codprodutovariacao
            limit 2500
            ";

        set_time_limit(600);
        
        //dd($sql);
                
        $regs = DB::select($sql);
        
        $gerados = [];
        
        $dataMov = new Carbon();
        
        foreach ($regs as $reg)
        {
            
            $sld = EstoqueSaldo::findOrFail($reg->codestoquesaldo);
            
            //dd($sld);
            
            $mesMov = EstoqueMes::buscaOuCria(
                    $reg->codprodutovariacao, 
                    $reg->codestoquelocal, 
                    $reg->fiscal, 
                    $dataMov
                    );

            $dataMov = new Carbon();
            
            $mov = new EstoqueMovimento();
            
            $mov->data = $dataMov;
            $mov->codestoquemes = $mesMov->codestoquemes;
            $mov->codestoquemovimentotipo = EstoqueMovimentoTipo::AJUSTE;
            $mov->manual = true;
            
            $quantidade = abs($sld->saldoquantidade);
            $valor = (double) $sld->customedio;
            
            // Tenta custo Medio pela ultima compra do estoque
            if (empty($valor))
            {
                $sql = "select em.entradavalor / em.entradaquantidade as custo
                        from tblestoquemovimento em
                        inner join tblestoquemes mes on (mes.codestoquemes = em.codestoquemes)
                        inner join tblestoquesaldo es on (es.codestoquesaldo = mes.codestoquesaldo and es.fiscal = false)
                        inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
                        inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
                        where em.codestoquemovimentotipo = 2001 -- COMPRA
                        and pv.codproduto = {$sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                        and coalesce(em.entradaquantidade, 0) > 0
                        order by data desc
                        limit 1
                    ";

                if ($custo = DB::select($sql))
                    $valor = $custo[0]->custo;
            }

            //Tenta pela media do custo medio fisico
            if (empty($valor))
            {
                $sql = "select avg(es.customedio) as custo
                        from tblprodutovariacao pv
                        inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                        inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false)
                        where pv.codproduto = {$sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                        and es.customedio is not null
                        and es.customedio > 0
                    ";

                if ($custo = DB::select($sql))
                    $valor = $custo[0]->custo;
            }

            //Tenta pela media do custo medio fisico
            if (empty($valor))
            {
                $sql = "select avg(es.customedio) as custo
                        from tblprodutovariacao pv
                        inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                        inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                        where pv.codproduto = {$sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                        and es.customedio is not null
                        and es.customedio > 0
                    ";

                if ($custo = DB::select($sql))
                    $valor = $custo[0]->custo;
            }

            //Tenta pela ultima nota de compra
            if (empty($valor))
            {
                $sql = "select (((nfpb.valortotal + nfpb.icmsstvalor + nfpb.ipivalor) * (1- (nf.valordesconto / nf.valorprodutos))) / nfpb.quantidade) / coalesce(pe.quantidade, 1) as custo, nf.codnotafiscal
                        from tblnotafiscal nf
                        inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscal = nf.codnotafiscal)
                        inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
                        inner join tblprodutovariacao pv on (pv.codprodutovariacao = pb.codprodutovariacao)
                        left join tblprodutoembalagem pe on (pe.codprodutoembalagem = pb.codprodutoembalagem)
                        where nf.codnaturezaoperacao = 4
                        and pv.codproduto = {$sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}
                        order by nf.saida desc
                        limit 1
                    ";

                if ($custo = DB::select($sql))
                    $valor = $custo[0]->custo;
            }

            if (empty($valor))
                $valor = $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->preco * 0.571428571; // 75% markup
            
            $valortotal = $valor * $quantidade;
            
            if ($sld->saldoquantidade < 0) {
                $mov->entradaquantidade = $quantidade;
                $mov->entradavalor = $valortotal;
            } else {
                $mov->saidaquantidade = $quantidade;
                $mov->saidavalor = $valortotal;
            }
            
            $mov->observacoes = 'Zeramento de Saldo Anterior ao Balanco';
            
            $sqls = DB::getQueryLog();

            if ($mov->save())
            {
                $gerados[] = [
                    'codproduto' => $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto,
                    'produto' => $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto,
                    'codprodutovariacao' => $sld->EstoqueLocalProdutoVariacao->codprodutovariacao,
                    'variacao' => $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao,
                    'codestoquemovimento' => $mov->codestoquemovimento,
                    'codestoquemes' => $mov->codestoquemes,
                    'entradaquantidade' => $mov->entradaquantidade,
                    'entradavalor' => $mov->entradavalor,
                    'saidaquantidade' => $mov->saidaquantidade,
                    'saidavalor' => $mov->saidavalor,
                ];
                $this->dispatch((new EstoqueCalculaCustoMedio($mov->codestoquemes))->onQueue('urgent'));
            }
            else 
            {
                echo "<h1>Erro ao salvar movimento</h1>";
                dd($mov);
            }
        }
        
        $sql = DB::getQueryLog();
        
        dd($gerados);
    }
*/
}
