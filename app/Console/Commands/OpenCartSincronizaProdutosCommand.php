<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Models\Marca;

use MGLara\Library\IntegracaoOpenCart\IntegracaoOpenCart;

class OpenCartSincronizaProdutosCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opencart:sincroniza-produtos {codproduto?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta Produtos e Cadastros Relacionados para o site OpenCart utilizando API';

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
        if (empty($this->argument('codproduto'))) {
            IntegracaoOpenCart::sincronizaProdutos();
        } else {
            IntegracaoOpenCart::sincronizaProdutos($this->argument('codproduto'));
        }
    }
    
}
