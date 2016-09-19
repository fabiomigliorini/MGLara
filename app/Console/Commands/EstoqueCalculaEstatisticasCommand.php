<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Jobs\EstoqueCalculaEstatisticas;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class EstoqueCalculaEstatisticasCommand extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estoque:calcula-estatisticas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula estatística de Venda da EstoqueLocalProdutoVariacao';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('EstoqueCalculaEstatisticasCommand - schedule');
        //$this->dispatch((new EstoqueCalculaEstatisticas(103, 103001))->onQueue('low'));
        $this->dispatch((new EstoqueCalculaEstatisticas(null, null))->onQueue('low'));
    }
    
}
