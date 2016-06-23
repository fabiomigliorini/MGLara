<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EstoqueGeraMovimentoNotaFiscal extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
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
    }
}
