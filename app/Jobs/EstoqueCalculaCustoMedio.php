<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use MGLara\Models\EstoqueMes;

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
