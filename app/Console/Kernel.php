<?php

namespace App\Console;

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
        \App\Console\Commands\ApplyCreditPenalties::class,
        \App\Console\Commands\SendBirthdayNotifications::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Apply credit penalties and mark overdue installments daily at 01:00
        $schedule->command('credits:apply-penalties')->dailyAt('01:00');

        // Send due/overdue notifications daily at 08:00
        $schedule->command('credits:notify-due')->dailyAt('08:00');

        // Send birthday notifications daily at 06:00
        $schedule->command('birthdays:send-notifications')->dailyAt('06:00');
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
