<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 


/**
 * @property bigint $codnegocio
 */

class EstoqueGeraMovimentoNegocio extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $codnegocio;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codnegocio)
    {
        $this->codnegocio = $codnegocio;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info('EstoqueGeraMovimentoNegocio', ['codnegocio' => $this->codnegocio]);
        
        //Agenda Calculo de todos os itens do negocio
        $sql = "select codnegocioprodutobarra from tblnegocioprodutobarra where codnegocio = {$this->codnegocio} order by codnegocioprodutobarra";
        $rows = DB::select($sql);
        
        foreach ($rows as $row) {
            $this->dispatch((new EstoqueGeraMovimentoNegocioProdutoBarra($row->codnegocioprodutobarra))->onQueue('high'));
        }
        
    }
}
