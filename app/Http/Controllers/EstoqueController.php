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
        //Delay de 2 segundos pra aguardar transação do Yii
        $this->dispatch((new EstoqueGeraMovimentoNegocio($id))->onQueue('medium')->delay(2));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoProduto(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoProduto($id))->onQueue('low'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoProdutoVariacao(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($id))->onQueue('low'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoPeriodo(Request $request)
    {
        $inicial = Carbon::createFromFormat('d/m/Y H:i:s', $request->inicial); // 1975-05-21 22:00:00
        $final = Carbon::createFromFormat('d/m/Y H:i:s', $request->final); // 1975-05-21 22:00:00

        $this->dispatch((new EstoqueGeraMovimentoPeriodo($inicial, $final))->onQueue('low'));
        
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
            
            foreach ($regs as $reg)
            {
                $es = EstoqueSaldo::buscaOuCria($reg->codprodutovariacao, $reg->codestoquelocal, false);

                $model = new EstoqueSaldoConferencia();

                $model->codestoquesaldo = $es->codestoquesaldo;
                $model->quantidadesistema = $es->saldoquantidade;
                $model->quantidadeinformada = $reg->quantidadeinformada;
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
                
                $model->data = new Carbon('2016-04-01');
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
    
    public function zeraSaldoNegativo ($id)
    {
        
        DB::enableQueryLog();
        
        $slds = EstoqueSaldo::join('tblestoquelocalprodutovariacao', function($join) {
            $join->on('tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao', '=', 'tblestoquesaldo.codestoquelocalprodutovariacao');
        })->where('tblestoquesaldo.saldoquantidade', '<', 0)
                ->where('tblestoquelocalprodutovariacao.codestoquelocal', $id)
                ->where('tblestoquesaldo.fiscal', false)
                ->orderBy('codestoquesaldo')
                ->limit(100)
                ->get();
        
        $gerados = [];
        
        $data = new Carbon('2016-04-01');
        
        foreach ($slds as $sld)
        {
            $mes = EstoqueMes::buscaOuCria(
                    $sld->EstoqueLocalProdutoVariacao->codprodutovariacao, 
                    $sld->EstoqueLocalProdutoVariacao->codestoquelocal, 
                    $sld->fiscal, 
                    $data
                    );
            $mov = new EstoqueMovimento();
            
            $mov->data = $data;
            $mov->codestoquemes = $mes->codestoquemes;
            $mov->codestoquemovimentotipo = EstoqueMovimentoTipo::AJUSTE;
            $mov->manual = true;
            $mov->entradaquantidade = abs($sld->saldoquantidade);
            
            $mov->entradavalor = (double) $sld->customedio;

            // Tenta custo Medio pela ultima compra do estoque
            if (empty($mov->entradavalor))
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
                    $mov->entradavalor = $custo[0]->custo;
            }

            //Tenta pela media do custo medio fisico
            if (empty($mov->entradavalor))
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
                    $mov->entradavalor = $custo[0]->custo;
            }

            //Tenta pela media do custo medio fisico
            if (empty($mov->entradavalor))
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
                    $mov->entradavalor = $custo[0]->custo;
            }

            //Tenta pela ultima nota de compra
            if (empty($mov->entradavalor))
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
                    $mov->entradavalor = $custo[0]->custo;
            }

            if (empty($mov->entradavalor))
                $mov->entradavalor = $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->preco * 0.571428571; // 75% markup
            
            $mov->entradavalor *= $mov->entradaquantidade;
            
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
        //dd($sql);
        dd($gerados);
    }
}
