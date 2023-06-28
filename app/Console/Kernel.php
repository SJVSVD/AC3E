<?php

namespace App\Console;
use App\Console\Commands\notasDeCredito;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(notasDeCredito::class)->everyMinute();
        $schedule->command('demo:cron')
                 ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        // $this->commands([
        //     notasDeCredito::class,
        // ]);

        require base_path('routes/console.php');
    }
}
