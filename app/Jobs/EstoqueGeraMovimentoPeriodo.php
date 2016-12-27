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
 * @property bool $fiscal
 * @property bool $fisico
 */

class EstoqueGeraMovimentoPeriodo extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $inicial;
    protected $final;
    protected $fiscal;
    protected $fisico;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Carbon $inicial, Carbon $final, $fisico = true, $fiscal = true)
    {
        $this->inicial = $inicial;
        $this->final = $final;
        $this->fisico = $fisico;
        $this->fiscal = $fiscal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EstoqueGeraMovimentoPeriodo', ['inicial' => $this->inicial, 'final' => $this->final]);
        
        if ($this->fisico) {
            
            $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISICO);

            $inicial = $this->inicial->max($corte);
            $final = $this->final->max($corte);

            $sql = "select codnegocio from tblnegocio where lancamento between '" . $inicial->format('Y-m-d H:i:s') . "' and '" . $final->format('Y-m-d H:i:s') . "' order by codnegocio";
            $rows = DB::select($sql);
            
            foreach ($rows as $row) {
                $this->dispatch((new EstoqueGeraMovimentoNegocio($row->codnegocio))->onQueue('medium'));
            }
        }

        if ($this->fiscal) {
            echo "fiscal";
            
            $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISCAL);

            $inicial = $this->inicial->max($corte);
            $final = $this->final->max($corte);

            $sql = "select codnotafiscal from tblnotafiscal where saida between '" . $inicial->format('Y-m-d H:i:s') . "' and '" . $final->format('Y-m-d H:i:s') . "' order by codnotafiscal";
            $rows = DB::select($sql);
            
            foreach ($rows as $row) {
                $this->dispatch((new EstoqueGeraMovimentoNotaFiscal($row->codnotafiscal))->onQueue('medium'));
            }
        }

        
    }
}
