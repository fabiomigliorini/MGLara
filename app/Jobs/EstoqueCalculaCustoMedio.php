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
 * @property $em EstoqueMes
 */

class EstoqueCalculaCustoMedio extends Job implements SelfHandling, ShouldQueue
{
    
    use DispatchesJobs;
    
    protected $em;
    protected $tentativa;


    use InteractsWithQueue, SerializesModels;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EstoqueMes $em, $tentativa = 0)
    {
        $this->em = $em;
        $this->tentativa = $tentativa;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . ' - EstoqueCalculaCustoMedio' . " - {$this->tentativa} - {$this->em->codestoquemes}\n", FILE_APPEND);
        
        if ($this->tentativa < 10)
        {
            $em = EstoqueMes::findOrFail($this->em->codestoquemes+1);
            $this->dispatch(new EstoqueCalculaCustoMedio($em, $this->tentativa + 1));
        }
    }
}
