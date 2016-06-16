<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use MGLara\Models\Negocio;

/**
 * @property Negocio $Negocio
 */

class EstoqueGeraMovimentoNegocio extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $Negocio;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Negocio $Negocio)
    {
        $this->Negocio = $Negocio;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        
        //Agenda Calculo de todos os itens do negocio
        foreach($this->Negocio->NegocioProdutoBarraS as $item)
            $this->dispatch(new EstoqueGeraMovimentoNegocioProdutoBarra($item));
        
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . " - EstoqueGeraMovimentoNegocio {$this->Negocio->codnegocio} \n", FILE_APPEND);        
    }
}
