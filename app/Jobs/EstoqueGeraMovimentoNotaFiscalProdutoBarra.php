<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use MGLara\Models\NotaFiscalProdutoBarra;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueLocal;
use MGLara\Models\Operacao;

class EstoqueGeraMovimentoNotaFiscalProdutoBarra extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $codnotafiscalprodutobarra;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codnotafiscalprodutobarra)
    {
        $this->codnotafiscalprodutobarra = $codnotafiscalprodutobarra;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EstoqueGeraMovimentoNotaFiscalProdutoBarra', ['codnotafiscalprodutobarra' => $this->codnotafiscalprodutobarra]);
        
        $nfpb = NotaFiscalProdutoBarra::findOrFail($this->codnotafiscalprodutobarra);
        
        $codestoquemes_recalcular = [];
        $codestoquemovimento_gerado = [];
        
        $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISCAL);
        
        if ($nfpb->NotaFiscal->ativa()
            && $nfpb->NotaFiscal->saida->gte($corte)
            && $nfpb->NotaFiscal->NaturezaOperacao->estoque == TRUE
            && $nfpb->ProdutoBarra->Produto->TipoProduto->estoque == TRUE        
            ) {
            
            $mov = EstoqueMovimento::where('codnotafiscalprodutobarra', $nfpb->codnotafiscalprodutobarra)->where('codestoquemovimentoorigem', null)->first();

            if ($mov == false) {
                $mov = new EstoqueMovimento;
            }
            
            $mes = EstoqueMes::buscaOuCria(
                $nfpb->ProdutoBarra->codprodutovariacao,
                $nfpb->NotaFiscal->codestoquelocal,
                true, 
                $nfpb->NotaFiscal->saida
                );
            
            if (!empty($mov->codestoquemes) && $mov->codestoquemes != $mes->codestoquemes) {
                $codestoquemes_recalcular[$mov->codestoquemes] = $mov->codestoquemes;
            }
            
            $mov->codestoquemes = $mes->codestoquemes;

            $mov->codestoquemovimentotipo = $nfpb->NotaFiscal->NaturezaOperacao->codestoquemovimentotipo;
            $mov->manual = false;
            $mov->data = $nfpb->NotaFiscal->saida;
            
            $quantidade = $nfpb->quantidade;
            
            if (!empty($nfpb->ProdutoBarra->codprodutoembalagem)) {
                $quantidade *= $nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade;
            }
            
            if ($nfpb->NotaFiscal->NaturezaOperacao->codoperacao == Operacao::ENTRADA) {
                if ($mov->entradaquantidade != $quantidade) {
                    $mov->entradaquantidade = $quantidade;
                }
                $mov->saidaquantidade = null;
            }
            else
            {
                $mov->entradaquantidade = null;
                if ($mov->saidaquantidade != $quantidade) {
                    $mov->saidaquantidade = $quantidade;
                }
            }
            
            
            if ($mov->EstoqueMovimentoTipo->preco == EstoqueMovimentoTipo::PRECO_INFORMADO) {
                
                $valor = $nfpb->valortotal;
                
                if ($nfpb->NotaFiscal->valordesconto > 0 && $nfpb->NotaFiscal->valorprodutos > 0) {
                    $valor *= 1 - ($nfpb->NotaFiscal->valordesconto / $nfpb->NotaFiscal->valorprodutos);
                }
                
                if ($nfpb->NotaFiscal->NaturezaOperacao->codoperacao == Operacao::ENTRADA) {
                    if ($mov->entradavalor != $valor) {
                        $mov->entradavalor = $valor;
                    }
                    $mov->saidavalor = null;
                } else {
                    $mov->entradavalor = null;
                    if ($mov->saidavalor != $valor) {
                        $mov->saidavalor = $valor;
                    }
                }

            }
            
            $mov->codnegocioprodutobarra = null;
            $mov->codnotafiscalprodutobarra = $nfpb->codnotafiscalprodutobarra;
            
            if ($mov->isDirty()) {
                
                $codestoquemes_recalcular[$mes->codestoquemes] = $mes->codestoquemes;

                $mov->alteracao = $nfpb->alteracao;
                $mov->codusuarioalteracao = $nfpb->codusuarioalteracao;
                $mov->criacao = $nfpb->criacao;
                $mov->codusuariocriacao = $nfpb->codusuariocriacao;

                // Se nao salvou, manda rodar novamente em 10 segundos
                if (!$mov->save()) {
                    $this->release(10);
                }
            }
            
            $codestoquemovimento_gerado[] = $mov->codestoquemovimento;

            /*

            //caso intercompany, gera movimento destino
            $localDest = EstoqueLocal::whereHas('Filial', function($iq) use ($nfpb){
                    $iq->where('codpessoa', $nfpb->NotaFiscal->codpessoa);
                })->first();
                
            $tipoDest = NULL;
            if ($localDest != NULL) {
                $tipoDest = $mov->EstoqueMovimentoTipo->EstoqueMovimentoTipoS->first();
            }
            
            if ($tipoDest != NULL) {
                
                $movDest = EstoqueMovimento::where('codnotafiscalprodutobarra', $nfpb->codnotafiscalprodutobarra)->where('codestoquemovimentoorigem', $mov->codestoquemovimento)->first();
                
                if ($movDest == false) {
                    $movDest = new EstoqueMovimento;
                }

                $mesDest = EstoqueMes::buscaOuCria(
                        $nfpb->ProdutoBarra->codprodutovariacao,
                        $localDest->codestoquelocal,
                        false, 
                        $nfpb->NotaFiscal->saida
                        );

                if (!empty($movDest->codestoquemes) && $movDest->codestoquemes != $mesDest->codestoquemes) {
                    $codestoquemes_recalcular[$movDest->codestoquemes] = $movDest->codestoquemes;
                }

                $movDest->codestoquemes = $mesDest->codestoquemes;
                $movDest->codestoquemovimentoorigem = $mov->codestoquemovimento;
                $movDest->codestoquemovimentotipo = $tipoDest->codestoquemovimentotipo;
                $movDest->manual = false;
                $movDest->data = $mov->data;
                $movDest->entradaquantidade = $mov->saidaquantidade;
                $movDest->entradavalor = $mov->saidavalor;
                $movDest->saidaquantidade = $mov->entradaquantidade;
                $movDest->saidavalor = $mov->entradavalor;
                $movDest->codnotafiscalprodutobarra = null;
                $movDest->codnotafiscalprodutobarra = $mov->codnotafiscalprodutobarra;

                if ($movDest->isDirty()) {
                    
                    $codestoquemes_recalcular[$mesDest->codestoquemes] = $mesDest->codestoquemes;
                
                    $movDest->alteracao = $mov->alteracao;
                    $movDest->codusuarioalteracao = $mov->codusuarioalteracao;
                    $movDest->criacao = $mov->criacao;
                    $movDest->codusuariocriacao = $mov->codusuariocriacao;

                    // Se nao salvou, manda rodar novamente em 10 segundos
                    if (!$movDest->save()) {
                        $this->release(10);
                    }
                }
                
                $codestoquemovimento_gerado[] = $movDest->codestoquemovimento;
                
            }
            */
        }
        
        // Apaga estoquemovimento excedente que existir anexado ao notafiscalprodutobarra
        $movExcedente = 
                EstoqueMovimento
                ::whereNotIn('codestoquemovimento', $codestoquemovimento_gerado)
                ->where('codnotafiscalprodutobarra', $nfpb->codnotafiscalprodutobarra)
                ->get();
        
        foreach ($movExcedente as $mov) {
            
            $codestoquemes_recalcular[$mov->codestoquemes] = $mov->codestoquemes;
            
            foreach ($mov->EstoqueMovimentoS as $movDest) {
                $codestoquemes_recalcular[$movDest->codestoquemes] = $movDest->codestoquemes;
                $movDest->codestoquemovimentoorigem = null;
                // Se nao salvou, manda rodar novamente em 10 segundos
                if (!$movDest->save()) {
                    $this->release(10);
                }
            }
            
            // Se nao excluiu, manda rodar novamente em 10 segundos
            if (!$mov->delete()) {
                $this->release(10);
            }
        }
                
        //Coloca Recalculo Custo Medio na Fila
        foreach($codestoquemes_recalcular as $codestoquemes => $mes) {
            echo("$codestoquemes\n<hr>");
            $this->dispatch((new EstoqueCalculaCustoMedio($codestoquemes))->onQueue('urgent'));
        }
        
    }
}
