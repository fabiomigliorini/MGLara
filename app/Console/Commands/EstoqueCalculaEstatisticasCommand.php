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
    protected $signature = 'estoque:calcula-estatisticas {codprodutovariacao?} {codestoquelocal?}';

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
        $codprodutovariacao = $this->argument('codprodutovariacao');
        $codestoquelocal = $this->argument('codestoquelocal');
        
        Log::info('EstoqueCalculaEstatisticasCommand');
        $this->dispatch((new EstoqueCalculaEstatisticas($codprodutovariacao, $codestoquelocal))->onQueue('medium'));
        
    }
    
}
