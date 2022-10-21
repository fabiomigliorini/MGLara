<?php

namespace MGLara\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \MGLara\Console\Commands\EstoqueAjustaFiscalCommand::class,
        \MGLara\Console\Commands\EstoqueCalculaEstatisticasCommand::class,
        \MGLara\Console\Commands\MagazordSincronizaPrecosCommand::class,
        \MGLara\Console\Commands\MagazordSincronizaSaldosCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('estoque:calcula-estatisticas')->dailyAt('00:00');
        $schedule->command('cache:clear')->dailyAt('01:00');
    }
}
