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
        // Daily scheduled task for contributions and penalties
        $schedule->call(function () {
            $service = app(\App\Services\ContributionService::class);
            // Generate monthly contributions for all users
            $service->generateMonthlyContributions();
            // Apply penalties automatically
            $service->applyPenalties();
        })->daily()
          ->withoutOverlapping()  // prevent multiple runs if one is still executing
          ->runInBackground();    // run asynchronously
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
