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

class EstoqueGeraMovimentoNotaFiscal extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $codnotafiscal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codnotafiscal)
    {
        $this->codnotafiscal = $codnotafiscal;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EstoqueGeraMovimentoNotaFiscal', ['codnotafiscal' => $this->codnotafiscal]);
        
        //Agenda Calculo de todos os itens do negocio
        $sql = "select codnotafiscalprodutobarra from tblnotafiscalprodutobarra where codnotafiscal = {$this->codnotafiscal} order by codnotafiscalprodutobarra";
        $rows = DB::select($sql);
        
        foreach ($rows as $row) {
            $this->dispatch((new EstoqueGeraMovimentoNotaFiscalProdutoBarra($row->codnotafiscalprodutobarra))->onQueue('high'));
        }
        
        
    }
}
