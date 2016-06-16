<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;

use MGLara\Models\Negocio;
use MGLara\Jobs\EstoqueGeraMovimentoNegocio;

class EstoqueGeraMovimentoPeriodo extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $inicial;
    protected $final;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Carbon $inicial, Carbon $final)
    {
        $this->inicial = $inicial;
        $this->final = $final;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $negocios = Negocio::whereBetween('lancamento', array($this->inicial, $this->final))->get();
        
        foreach($negocios as $negocio)
            $this->dispatch(new EstoqueGeraMovimentoNegocio($negocio));
        
        /*
        foreach ($this->Periodo->PeriodoBarraS as $pb)
            foreach ($pb->NegocioPeriodoBarraS as $npb)
                $this->dispatch(new EstoqueGeraMovimentoNegocioPeriodoBarra($npb));
        */
        
        //
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . ' - EstoqueGeraMovimentoPeriodo ' . "$this->inicial - $this->final\n", FILE_APPEND);
    }
}
