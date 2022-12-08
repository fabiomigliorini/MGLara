<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;

class MercosSincronizaCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercos:sincroniza';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizacao de Preços/Estoques/Produtos/Clientes/Pedidos com o site Mercos utilizando API';

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
        $this->call('mercos:sincroniza-produtos');
        $this->call('mercos:importa-clientes');
        $this->call('mercos:importa-pedidos');
    }

}
