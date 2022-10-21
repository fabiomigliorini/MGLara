<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Library\Magazord\Magazord;

class MagazordSincronizaSaldosCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magazord:sincroniza-saldos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta Saldos de Estoque para o site Magazord utilizando API';

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
        $count = 1000;
        do {
            $sincronizados = Magazord::sincronizaSaldos($count);
        } while ($sincronizados == $count);
    }

}
