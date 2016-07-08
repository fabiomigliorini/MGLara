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
    
    public function geraSaldoConferenciaNegocio(Request $request, $id)
    {
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
        
        DB::beginTransaction();
        
        try {
            
            $regs = DB::select($sql);

            foreach ($regs as $reg)
            {
                $es = EstoqueSaldo::buscaOuCria($reg->codprodutovariacao, $reg->codestoquelocal, false);

                $model = new EstoqueSaldoConferencia();

                $model->codestoquesaldo = $es->codestoquesaldo;
                $model->quantidadesistema = $es->saldoquantidade;
                $model->quantidadeinformada = $reg->quantidadeinformada;
                $model->customediosistema = $es->customedio;

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

                $model->data = new Carbon('2016-04-01');
                $model->observacoes = "Criado a partir do Negocio " . formataCodigo($id);

                if (!$model->save())
                    throw new Exception ('Erro ao Salvar EstoqueSaldoConferencia');

                $model->EstoqueSaldo->ultimaconferencia = $model->criacao;
                if (!$model->EstoqueSaldo->save())
                    throw new Exception ('Erro ao Salvar EstoqueSaldo');
                
                DB::commit();

                $this->dispatch((new EstoqueGeraMovimentoConferencia($model->codestoquesaldoconferencia))->onQueue('urgent'));
                
                $gerado[] = [
                    'codestoquesaldoconferencia' => $model->codestoquesaldoconferencia,
                    'codestoquesaldo' => $model->codestoquesaldo,
                    'codproduto' => $es->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto,
                    'codprodutovariacao' => $es->EstoqueLocalProdutoVariacao->codprodutovariacao,
                    'quantidadeinformada' => $model->quantidadeinformada,
                    'customedio' => $model->customedioinformado,
                    'codestoquelocal' => $es->EstoqueLocalProdutoVariacao->codestoquelocal,
                        ];

            }
            
            return response()->json(['response' => 'Gerado', 'registros' => $gerado]);
            
        } catch (Exception $exc) {
            DB:rollback();
            dd($exc);
        }



        
        /*
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
        
        */
    }
}
