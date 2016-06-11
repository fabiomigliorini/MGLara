<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use MGLara\Models\Produto;

class EstoqueGeraMovimentoProduto extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $Produto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Produto $Produto)
    {
        $this->Produto = $Produto;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        foreach ($this->Produto->ProdutoBarraS as $pb)
            foreach ($pb->NegocioProdutoBarraS as $npb)
                $this->dispatch(new EstoqueGeraMovimentoNegocioProdutoBarra($npb));
                
        //
        file_put_contents('/tmp/jobs.log', date('d/m/Y h:i:s') . ' - EstoqueGeraMovimentoProduto' . "\n", FILE_APPEND);
    }
}
