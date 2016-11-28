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

use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\NegocioStatus;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueLocal;
use MGLara\Models\Operacao;
//use MGLara\Models\Filial;
use MGLara\Models\EstoqueMovimentoTipo;

/**
 * @property bigint $codnegocioprodutobarra
 * @property NegocioProdutoBarra $NegocioProdutoBarra
 */

class EstoqueGeraMovimentoNegocioProdutoBarra extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $codnegocioprodutobarra;
    protected $NegocioProdutoBarra;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codnegocioprodutobarra)
    {
        //
        $this->codnegocioprodutobarra = $codnegocioprodutobarra;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EstoqueGeraMovimentoNegocioProdutoBarra', ['codnegocioprodutobarra' => $this->codnegocioprodutobarra]);

        $this->NegocioProdutoBarra = NegocioProdutoBarra::findOrFail($this->codnegocioprodutobarra);
        
        $codestoquemes_recalcular = [];
        $codestoquemovimento_gerado = [];
        
        $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISICO);
        
        if ($this->NegocioProdutoBarra->Negocio->codnegociostatus == NegocioStatus::FECHADO
            && $this->NegocioProdutoBarra->Negocio->lancamento->gte($corte)
            && $this->NegocioProdutoBarra->Negocio->NaturezaOperacao->estoque == TRUE
            && $this->NegocioProdutoBarra->ProdutoBarra->Produto->TipoProduto->estoque == TRUE        
            ) {
            
            $mov = EstoqueMovimento::where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)->where('codestoquemovimentoorigem', null)->first();

            if ($mov == false) {
                $mov = new EstoqueMovimento;
            }
            
            $mes = EstoqueMes::buscaOuCria(
                $this->NegocioProdutoBarra->ProdutoBarra->codprodutovariacao,
                $this->NegocioProdutoBarra->Negocio->codestoquelocal,
                false, 
                $this->NegocioProdutoBarra->Negocio->lancamento
                );
            
            if (!empty($mov->codestoquemes) && $mov->codestoquemes != $mes->codestoquemes) {
                $codestoquemes_recalcular[$mov->codestoquemes] = $mov->codestoquemes;
            }
            
            $mov->codestoquemes = $mes->codestoquemes;

            $mov->codestoquemovimentotipo = $this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codestoquemovimentotipo;
            $mov->manual = false;
            $mov->data = $this->NegocioProdutoBarra->Negocio->lancamento;
            
            $quantidade = $this->NegocioProdutoBarra->quantidade;
            
            if (!empty($this->NegocioProdutoBarra->ProdutoBarra->codprodutoembalagem)) {
                $quantidade *= $this->NegocioProdutoBarra->ProdutoBarra->ProdutoEmbalagem->quantidade;
            }
            
            if ($this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codoperacao == Operacao::ENTRADA) {
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
                
                $valor = $this->NegocioProdutoBarra->valortotal;
                
                if ($this->NegocioProdutoBarra->Negocio->valordesconto > 0 && $this->NegocioProdutoBarra->Negocio->valorprodutos > 0) {
                    $valor *= 1 - ($this->NegocioProdutoBarra->Negocio->valordesconto / $this->NegocioProdutoBarra->Negocio->valorprodutos);
                }
                
                if ($this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codoperacao == Operacao::ENTRADA) {
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
            
            $mov->codnotafiscalprodutobarra = null;
            $mov->codnegocioprodutobarra = $this->NegocioProdutoBarra->codnegocioprodutobarra;
            
            if ($mov->isDirty()) {
                
                $codestoquemes_recalcular[$mes->codestoquemes] = $mes->codestoquemes;

                $mov->alteracao = $this->NegocioProdutoBarra->alteracao;
                $mov->codusuarioalteracao = $this->NegocioProdutoBarra->codusuarioalteracao;
                $mov->criacao = $this->NegocioProdutoBarra->criacao;
                $mov->codusuariocriacao = $this->NegocioProdutoBarra->codusuariocriacao;

                // Se nao salvou, manda rodar novamente em 10 segundos
                if (!$mov->save()) {
                    $this->release(10);
                }
            }
            
            $codestoquemovimento_gerado[] = $mov->codestoquemovimento;

            //caso intercompany, gera movimento destino
            $localDest = EstoqueLocal::whereHas('Filial', function($iq){
                    $iq->where('codpessoa', $this->NegocioProdutoBarra->Negocio->codpessoa);
                })->first();
            
            $tipoDest = NULL;
            if ($localDest != NULL) {
                $tipoDest = $mov->EstoqueMovimentoTipo->EstoqueMovimentoTipoS->first();
            }
            
            if ($tipoDest != NULL) {
                
                $movDest = EstoqueMovimento::where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)->where('codestoquemovimentoorigem', $mov->codestoquemovimento)->first();
                
                if ($movDest == false) {
                    $movDest = new EstoqueMovimento;
                }

                $mesDest = EstoqueMes::buscaOuCria(
                        $this->NegocioProdutoBarra->ProdutoBarra->codprodutovariacao,
                        $localDest->codestoquelocal,
                        false, 
                        $this->NegocioProdutoBarra->Negocio->lancamento
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
                $movDest->codnegocioprodutobarra = $mov->codnegocioprodutobarra;

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
            
        }

        // Apaga estoquemovimento excedente que existir anexado ao negocioprodutobarra
        $movExcedente = 
                EstoqueMovimento
                ::whereNotIn('codestoquemovimento', $codestoquemovimento_gerado)
                ->where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)
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
