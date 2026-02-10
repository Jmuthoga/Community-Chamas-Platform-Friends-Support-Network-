<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example of default Laravel command
        // $schedule->command('inspire')->hourly();

        // Your custom scheduled tasks
        $schedule->call(function () {
            $service = app(\App\Services\ContributionService::class);
            $service->generateMonthlyContributions();
            $service->applyPenalties();
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
