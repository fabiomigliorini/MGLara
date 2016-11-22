<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use MGLara\Models\EstoqueMes;
use MGLara\Jobs\EstoqueGeraMovimentoNegocio;

/**
 * @property Carbon $inicial
 * @property Carbon $final
 */

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
        Log::info('EstoqueGeraMovimentoPeriodo', ['inicial' => $this->inicial, 'final' => $this->final]);

        $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISICO);
        
        $this->inicial = $this->inicial->max($corte);
        $this->final = $this->final->max($corte);

        $sql = "select codnegocio from tblnegocio where lancamento between '" . $this->inicial->format('Y-m-d H:i:s') . "' and '" . $this->final->format('Y-m-d H:i:s') . "' order by codnegocio";
        $rows = DB::select($sql);
        foreach ($rows as $row) {
            $this->dispatch((new EstoqueGeraMovimentoNegocio($row->codnegocio))->onQueue('medium'));
        }
        
    }
}
