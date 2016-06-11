<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimentoTipo;
use Illuminate\Support\Facades\DB;

/**
 * @property $EstoqueMes EstoqueMes
 */

class EstoqueCalculaCustoMedio extends Job implements SelfHandling, ShouldQueue
{
    
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $EstoqueMes;
    protected $tentativa;


    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EstoqueMes $EstoqueMes, $tentativa = 0)
    {
        $this->EstoqueMes = $EstoqueMes;
        $this->tentativa = $tentativa;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mes = $this->EstoqueMes;
        
        $sql = "
            select 
                sum(entradaquantidade) entradaquantidade
                , sum(entradavalor) entradavalor
                , sum(saidaquantidade) saidaquantidade
                , sum(saidavalor) saidavalor
            from tblestoquemovimento mov
            left join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
            where mov.codestoquemes = {$mes->codestoquemes}
            and tipo.preco in (" . EstoqueMovimentoTipo::PRECO_INFORMADO . ", " . EstoqueMovimentoTipo::PRECO_ORIGEM . ")";

        $mov = DB::select($sql);
        $mov = $mov[0];

        $inicialquantidade = 0;
        $inicialvalor = 0;        
        $entradaquantidade = $mov->entradaquantidade;
        $entradavalor = $mov->entradavalor;
        $saidaquantidade = $mov->saidaquantidade;
        $saidavalor = $mov->saidavalor;
        $saldoquantidade = $inicialquantidade + $entradaquantidade - $saidaquantidade;
        $saldovalor = $inicialvalor + $entradavalor - $saidavalor;

        $customedio = null;
        if (($entradaquantidade + $inicialquantidade) > 0)
            $customedio = ($entradavalor + $inicialvalor)/($entradaquantidade + $inicialquantidade);


        foreach ($mes->EstoqueMovimentoS as $mov)
        {
            if ($mov->EstoqueMovimentoTipo->preco != EstoqueMovimentoTipo::PRECO_MEDIO)
                continue;

            $mov->entradavalor = (!empty($mov->entradaquantidade))?round($mov->entradaquantidade * $customedio, 2):null;
            $mov->saidavalor = (!empty($mov->saidaquantidade))?round($mov->saidaquantidade * $customedio, 2):null;
            $mov->save();

            $entradaquantidade += $mov->entradaquantidade;
            $entradavalor += $mov->entradavalor;
            $saidaquantidade += $mov->saidaquantidade;
            $saidavalor += $mov->saidavalor;

            foreach ($mov->EstoqueMovimentoS as $movfilho)
            {
                if ($movfilho->EstoqueMovimentoTipo->preco != EstoqueMovimentoTipo::PRECO_ORIGEM)
                    continue;

                $movfilho->entradavalor = (!empty($movfilho->entradaquantidade))?round($movfilho->entradaquantidade * $customedio, 2):null;
                $movfilho->saidavalor = (!empty($movfilho->saidaquantidade))?round($movfilho->saidaquantidade * $customedio, 2):null;
                $movfilho->save();
            }
        }

        $saldoquantidade = $inicialquantidade + $entradaquantidade - $saidaquantidade;
        $saldovalor = $inicialvalor + $entradavalor - $saidavalor;

        $customedio = null;
        if (($entradaquantidade + $inicialquantidade) > 0)
            $customedio = ($entradavalor + $inicialvalor)/($entradaquantidade + $inicialquantidade);

        if ($saldoquantidade == 0)
            $saldovalor = 0;

        $mes->inicialquantidade = $inicialquantidade;
        $mes->inicialvalor = $inicialvalor;
        $mes->entradaquantidade = $entradaquantidade;
        $mes->entradavalor = $entradavalor;
        $mes->saidaquantidade = $saidaquantidade;
        $mes->saidavalor = $saidavalor;
        $mes->saldoquantidade = $saldoquantidade;
        $mes->saldovalor = $saldovalor;
        $mes->customedio = $customedio;

        $mes->save();

        $inicialquantidade = $saldoquantidade;
        $inicialvalor = $saldovalor;

        
        //$this->EstoqueMes->EstoqueSaldo->recalculaCustoMedio();
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . ' - EstoqueCalculaCustoMedio' . " - {$this->tentativa} - {$this->EstoqueMes->codestoquemes}\n", FILE_APPEND);
        /*
        if ($this->tentativa < 10)
        {
            $EstoqueMes = EstoqueMes::findOrFail($this->EstoqueMes->codestoquemes+1);
            $this->dispatch(new EstoqueCalculaCustoMedio($EstoqueMes, $this->tentativa + 1));
        }
         * 
         */
    }
}
