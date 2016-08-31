<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Jobs\EstoqueCalculaVenda as JobEstoqueCalculaVenda;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class EstoqueCalculaVenda extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estoque:calcula-venda';

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
        Log::info('EstoqueCalculaVenda - schedule');
        $this->dispatch((new JobEstoqueCalculaVenda(null, null))->onQueue('low'));
    }
    
}
