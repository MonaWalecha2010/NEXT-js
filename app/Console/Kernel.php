<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\TaskStatusUpdate::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('telescope:prune --hours=48')->daily();
        $schedule->command('app:task-status-update')->daily();
        // $schedule->command('app:task-status-update')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
