<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Library\Magazord\Magazord;

class MagazordSincronizaPrecosCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magazord:sincroniza-precos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta Preços para o site Magazord utilizando API';

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
        $count = 50;
        do {
            $sincronizados = Magazord::sincronizaPrecos($count);
        } while ($sincronizados == $count);
    }

}
