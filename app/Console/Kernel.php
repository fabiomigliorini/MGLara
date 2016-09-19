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
        \MGLara\Console\Commands\Inspire::class,
        \MGLara\Console\Commands\EstoqueCalculaEstatisticasCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('estoque:calcula-estatisticas')->dailyAt('01:00');
        //$schedule->command('inspire')->hourly();
    }
}
