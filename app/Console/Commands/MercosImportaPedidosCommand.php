<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Library\Mercos\MercosPedido;

class MercosImportaPedidosCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercos:importa-pedidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa Pedidos do site Mercos utilizando API';

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
        MercosPedido::importaPedidoApos('ultima');
    }

}
