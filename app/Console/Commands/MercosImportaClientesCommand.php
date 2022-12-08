<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Library\Mercos\MercosCliente;

class MercosImportaClientesCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercos:importa-clientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa Clientes do site Mercos utilizando API';

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
        MercosCliente::importaClienteApos('ultima');
    }

}
