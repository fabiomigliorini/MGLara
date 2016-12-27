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
use MGLara\Models\NotaFiscal;
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
            
            // Vincula movimento de origem
            if (!empty($nfpb->codnotafiscalprodutobarraorigem)) {
                if ($orig = $nfpb->NotaFiscalProdutoBarraOrigem->EstoqueMovimentoS->first()) {
                    $mov->codestoquemovimentoorigem = $orig->codestoquemovimento;
                }
            } elseif ($mov->EstoqueMovimentoTipo->preco == EstoqueMovimentoTipo::PRECO_ORIGEM) {
                foreach ($nfpb->NotaFiscal->NotaFiscalReferenciadaS as $nferef) {
                    foreach (NotaFiscal::where('nfechave', '=', $nferef->nfechave)->get() as $nf_origem) {
                        if ($nfpb_origem = $nf_origem->NotaFiscalProdutoBarraS()->where('codprodutobarra', '=', $nfpb->codprodutobarra)->first()) {
                            if ($orig = $nfpb_origem->EstoqueMovimentoS->first()) {
                                $mov->codestoquemovimentoorigem = $orig->codestoquemovimento;
                            }
                        }
                    }
                }
            }
            
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
            
            // Vincula movimentos de destino
            foreach ($nfpb->NotaFiscalProdutoBarraS as $nfpb_dest) {
                foreach ($nfpb_dest->EstoqueMovimentoS as $dest) {
                    $dest->codestoquemovimentoorigem = $mov->codestoquemovimento;
                    if ($dest->isDirty()) {
                        $codestoquemes_recalcular[$dest->codestoquemes] = $dest->codestoquemes;
                        if (!$dest->save()) {
                            $this->release(10);
                        }
                    }
                }
            }
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
            $this->dispatch((new EstoqueCalculaCustoMedio($codestoquemes))->onQueue('urgent'));
        }
        
    }
}
