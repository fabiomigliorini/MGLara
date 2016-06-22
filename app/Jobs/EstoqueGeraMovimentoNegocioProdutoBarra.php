<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;

use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\NegocioStatus;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueLocal;
use MGLara\Models\Operacao;
use MGLara\Models\Filial;
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

        $this->NegocioProdutoBarra = NegocioProdutoBarra::findOrFail($this->codnegocioprodutobarra);
        
        $mesRecalcular = [];
        $codestoquemovimentoGerado = [];
        
        $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISICO);
        
        if ($this->NegocioProdutoBarra->Negocio->codnegociostatus == NegocioStatus::FECHADO
                && $this->NegocioProdutoBarra->Negocio->NaturezaOperacao->estoque == TRUE
                && $this->NegocioProdutoBarra->Negocio->lancamento->gte($corte)
                )
        {
            $mov = EstoqueMovimento::where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)->where('codestoquemovimentoorigem', null)->first();

            if ($mov == false)
                $mov = new EstoqueMovimento;
            
            $mes = EstoqueMes::buscaOuCria(
                    $this->NegocioProdutoBarra->ProdutoBarra->codproduto,
                    $this->NegocioProdutoBarra->Negocio->codestoquelocal,
                    false, 
                    $this->NegocioProdutoBarra->Negocio->lancamento
                    );
            
            if (!empty($mov->codestoquemes) && $mov->codestoquemes != $mes->codestoquemes)
                $mesRecalcular[] = $mov->codestoquemes;
            
            $mov->codestoquemes = $mes->codestoquemes;

            $mov->codestoquemovimentotipo = $this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codestoquemovimentotipo;
            $mov->manual = false;
            $mov->data = $this->NegocioProdutoBarra->Negocio->lancamento;
            
            $quantidade = $this->NegocioProdutoBarra->quantidade;
            
            if (!empty($this->NegocioProdutoBarra->ProdutoBarra->codprodutoembalagem))
                $quantidade *= $this->NegocioProdutoBarra->ProdutoBarra->ProdutoEmbalagem->quantidade;
            
            if ($this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codoperacao == Operacao::ENTRADA)
            {
                if ($mov->entradaquantidade != $quantidade)
                    $mov->entradaquantidade = $quantidade;
                $mov->saidaquantidade = null;
            }
            else
            {
                $mov->entradaquantidade = null;
                if ($mov->saidaquantidade != $quantidade)
                    $mov->saidaquantidade = $quantidade;
            }
            
            if ($mov->EstoqueMovimentoTipo->preco == \MGLara\Models\EstoqueMovimentoTipo::PRECO_INFORMADO)
            {
                $valor = $this->NegocioProdutoBarra->valortotal;
                
                if ($this->NegocioProdutoBarra->Negocio->valordesconto > 0 && $this->NegocioProdutoBarra->Negocio->valorprodutos > 0)
                    $valor *= 1 - ($this->NegocioProdutoBarra->Negocio->valordesconto / $this->NegocioProdutoBarra->Negocio->valorprodutos);
                
                if ($this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codoperacao == Operacao::ENTRADA)
                {
                    if ($mov->entradavalor != $valor)
                        $mov->entradavalor = $valor;
                    $mov->saidavalor = null;
                }
                else
                {
                    $mov->entradavalor = null;
                    if ($mov->saidavalor != $valor);
                    $mov->saidavalor = $valor;
                }

            }
            
            $mov->codnotafiscalprodutobarra = null;
            $mov->codnegocioprodutobarra = $this->NegocioProdutoBarra->codnegocioprodutobarra;
            
            if ($mov->isDirty())
            {
                /*
                $alteracao = $mov->getDirty();
                dd($mov);
                file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . " - isDirty 1 $alteracao \n", FILE_APPEND);                
                */
                $mesRecalcular[] = $mes->codestoquemes;

                $mov->alteracao = $this->NegocioProdutoBarra->alteracao;
                $mov->codusuarioalteracao = $this->NegocioProdutoBarra->codusuarioalteracao;
                $mov->criacao = $this->NegocioProdutoBarra->criacao;
                $mov->codusuariocriacao = $this->NegocioProdutoBarra->codusuariocriacao;

                $mov->save();
            }
            
            $codestoquemovimentoGerado[] = $mov->codestoquemovimento;

            //caso intercompany, gera movimento destino
            $localDest = EstoqueLocal::whereHas('Filial', function($iq){
                    $iq->where('codpessoa', $this->NegocioProdutoBarra->Negocio->codpessoa);
                })->first();
            
            $tipoDest = NULL;
            if ($localDest != NULL)
                $tipoDest = $mov->EstoqueMovimentoTipo->EstoqueMovimentoTipoS->first();
            
            if ($tipoDest != NULL)
            {               
                $movDest = EstoqueMovimento::where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)->where('codestoquemovimentoorigem', $mov->codestoquemovimento)->first();
                
                if ($movDest == false)
                    $movDest = new EstoqueMovimento;

                $mesDest = EstoqueMes::buscaOuCria(
                        $this->NegocioProdutoBarra->ProdutoBarra->codproduto,
                        $localDest->codestoquelocal,
                        false, 
                        $this->NegocioProdutoBarra->Negocio->lancamento
                        );

                if (!empty($movDest->codestoquemes) && $movDest->codestoquemes != $mesDest->codestoquemes)
                    $mesRecalcular[] = $movDest->codestoquemes;

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

                if ($movDest->isDirty())
                {
                    /*
                    $alteracao = $movDest->getDirty();
                    file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . " - isDirty 2 - $alteracao \n", FILE_APPEND);                
                    */
                    $mesRecalcular[] = $mesDest->codestoquemes;
                
                    $movDest->alteracao = $mov->alteracao;
                    $movDest->codusuarioalteracao = $mov->codusuarioalteracao;
                    $movDest->criacao = $mov->criacao;
                    $movDest->codusuariocriacao = $mov->codusuariocriacao;

                    $movDest->save();
                }
                
                $codestoquemovimentoGerado[] = $movDest->codestoquemovimento;
                
            }
            
        }

        //Apaga estoquemovimento excedente que existir anexado ao negocioprodutobarra
        $movExcedente = 
                EstoqueMovimento
                ::whereNotIn('codestoquemovimento', $codestoquemovimentoGerado)
                ->where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)
                ->get();
        foreach ($movExcedente as $mov)
        {
            $mesRecalcular[] = $mov->codestoquemes;
            foreach ($mov->EstoqueMovimentoS as $movDest)
            {
                $movDest->codestoquemovimentoorigem = null;
                $movDest->save();
            }
            $mov->delete();
        }
                
        //Coloca Recalculo Custo Medio na Fila
        foreach($mesRecalcular as $mes)
            $this->dispatch((new EstoqueCalculaCustoMedio($mes))->onQueue('urgent'));
        
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . " - EstoqueGeraMovimentoNegocioProdutoBarra {$this->NegocioProdutoBarra->codnegocio} - {$this->NegocioProdutoBarra->codnegocioprodutobarra} \n", FILE_APPEND);                
    }
}
