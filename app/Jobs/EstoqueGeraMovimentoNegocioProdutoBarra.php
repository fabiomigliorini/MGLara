<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\NegocioStatus;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\Operacao;

/**
 * @property NegocioProdutoBarra $NegocioProdutoBarra
 */

class EstoqueGeraMovimentoNegocioProdutoBarra extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $NegocioProdutoBarra;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NegocioProdutoBarra $NegocioProdutoBarra)
    {
        //
        $this->NegocioProdutoBarra = $NegocioProdutoBarra;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $mesRecalcular = [];
        $codestoquemovimentoGerado = [];
        
        if ($this->NegocioProdutoBarra->Negocio->codnegociostatus == NegocioStatus::FECHADO)
        {
            $mov = EstoqueMovimento::where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)->where('codestoquemovimentoorigem', null)->first();

            if ($mov == false)
            {
                $mov = new EstoqueMovimento;
            }
            
            $mes = EstoqueMes::buscaOuCria(
                    $this->NegocioProdutoBarra->ProdutoBarra->codproduto,
                    $this->NegocioProdutoBarra->Negocio->codestoquelocal,
                    false, 
                    $this->NegocioProdutoBarra->Negocio->lancamento
                    );
            
            $mesRecalcular[] = $mes;
            
            $mov->codestoquemes = $mes->codestoquemes;
            $mov->codestoquemovimentotipo = $this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codestoquemovimentotipo;
            $mov->manual = false;
            $mov->data = $this->NegocioProdutoBarra->Negocio->lancamento;
            
            if ($this->NegocioProdutoBarra->Negocio->NaturezaOperacao->codoperacao == Operacao::ENTRADA)
            {
                $mov->entradaquantidade = $this->NegocioProdutoBarra->quantidade;
                $mov->entradavalor = $this->NegocioProdutoBarra->valortotal;
                $mov->saidaquantidade = null;
                $mov->saidavalor = null;
            }
            else
            {
                $mov->entradaquantidade = null;
                $mov->entradavalor = null;
                $mov->saidaquantidade = $this->NegocioProdutoBarra->quantidade;
                $mov->saidavalor = $this->NegocioProdutoBarra->valortotal;
            }
            
            $mov->codnotafiscalprodutobarra = null;
            $mov->codnegocioprodutobarra = $this->NegocioProdutoBarra->codnegocioprodutobarra;
            
            $mov->alteracao = $this->NegocioProdutoBarra->alteracao;
            $mov->codusuarioalteracao = $this->NegocioProdutoBarra->codusuarioalteracao;
            $mov->criacao = $this->NegocioProdutoBarra->criacao;
            $mov->codusuariocriacao = $this->NegocioProdutoBarra->codusuariocriacao;
            
            $mov->save();
            
            $codestoquemovimentoGerado[] = $mov->codestoquemovimento;
            
        }

        //Apaga estoquemovimento excedente que existir anexado ao negocioprodutobarra
        $movExcedente = 
                EstoqueMovimento
                ::whereNotIn('codestoquemovimento', $codestoquemovimentoGerado)
                ->where('codnegocioprodutobarra', $this->NegocioProdutoBarra->codnegocioprodutobarra)
                ->get();
        foreach ($movExcedente as $mov)
        {
            $mesRecalcular[] = $mov->EstoqueMes;
            $mov->delete();
        }
                
        //Coloca Recalculo Custo Medio na Fila
        foreach($mesRecalcular as $mes)
            $this->dispatch(new EstoqueCalculaCustoMedio($mes));
        
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . ' - EstoqueGeraMovimentoNegocioProdutoBarra' . "\n", FILE_APPEND);                
    }
}
