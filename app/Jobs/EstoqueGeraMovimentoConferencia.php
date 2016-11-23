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

/*
use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\NegocioStatus;
use MGLara\Models\EstoqueLocal;
use MGLara\Models\Operacao;
use MGLara\Models\Filial;
use MGLara\Models\EstoqueMovimentoTipo;
*/

use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueSaldoConferencia;

/**
 * @property bigint $codestoquesaldoconferencia
 * @property EstoqueSaldoConferencia $EstoqueSaldoConferencia
 */

class EstoqueGeraMovimentoConferencia extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $codestoquesaldoconferencia;
    protected $EstoqueSaldoConferencia;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codestoquesaldoconferencia)
    {
        //
        $this->codestoquesaldoconferencia = $codestoquesaldoconferencia;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EstoqueGeraMovimentoConferencia', ['codestoquesaldoconferencia' => $this->codestoquesaldoconferencia]);
        
        $this->EstoqueSaldoConferencia = EstoqueSaldoConferencia::findOrFail($this->codestoquesaldoconferencia);
        
        $codestoquemesRecalcular = [];
        $codestoquemovimentoGerado = [];
        
        $quantidade = $this->EstoqueSaldoConferencia->quantidadeinformada - $this->EstoqueSaldoConferencia->quantidadesistema;
        $valor = $quantidade * $this->EstoqueSaldoConferencia->customedioinformado;
        
        if ($quantidade == 0 && $valor == 0)
            return;
        
        $mov = $this->EstoqueSaldoConferencia->EstoqueMovimentoS->first();
        
        if ($mov == false)
            $mov = new EstoqueMovimento;
        
        $mes = EstoqueMes::buscaOuCria(
                $this->EstoqueSaldoConferencia->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao, 
                $this->EstoqueSaldoConferencia->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal, 
                $this->EstoqueSaldoConferencia->EstoqueSaldo->fiscal, 
                $this->EstoqueSaldoConferencia->data);
        
        $codestoquemesRecalcular[] = $mes->codestoquemes;
        
        if (!empty($mov->codestoquemes) && $mov->codestoquemes != $mes->codestoquemes)
            $codestoquemesRecalcular[] = $mov->codestoquemes;
            
        $mov->codestoquemes = $mes->codestoquemes;
        $mov->codestoquemovimentotipo = EstoqueMovimentoTipo::AJUSTE;
        $mov->manual = false;
        $mov->data = $this->EstoqueSaldoConferencia->data;
        
        if ($quantidade >= 0)
        {
            $mov->entradaquantidade = $quantidade;
            $mov->saidaquantidade = null;
        }
        else
        {
            $mov->entradaquantidade = null;
            $mov->saidaquantidade = abs($quantidade);
        }
        
        if ($valor >= 0)
        {
            $mov->entradavalor = $valor;
            $mov->saidavalor = null;
        }
        else
        {
            $mov->entradavalor = null;
            $mov->saidavalor = abs($valor);
        }
        
        $mov->codestoquesaldoconferencia = $this->EstoqueSaldoConferencia->codestoquesaldoconferencia;
        
        $mov->save();
        
        //armazena estoquemovimento gerado
        $codestoquemovimentoGerado[] = $mov->codestoquemovimento;

        //Apaga estoquemovimento excedente que existir anexado ao negocioprodutobarra
        $movExcedente = 
                EstoqueMovimento
                ::whereNotIn('codestoquemovimento', $codestoquemovimentoGerado)
                ->where('codestoquesaldoconferencia', $this->EstoqueSaldoConferencia->codestoquesaldoconferencia)
                ->get();
        foreach ($movExcedente as $mov)
        {
            $codestoquemesRecalcular[] = $mov->codestoquemes;
            foreach ($mov->EstoqueMovimentoS as $movDest)
            {
                $movDest->codestoquemovimentoorigem = null;
                $movDest->save();
            }
            $mov->delete();
        }
        
        //Coloca Recalculo Custo Medio na Fila
        foreach($codestoquemesRecalcular as $codestoquemes) {
            $this->dispatch((new EstoqueCalculaCustoMedio($codestoquemes))->onQueue('urgent'));
        }
        
    }
}
