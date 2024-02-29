<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('otp:clean')->daily();  // clean expired otp
        $schedule->command('recurring:payments')->dailyAt('12:00');
        $schedule->command('subscriptions:check-expiration')->dailyAt('12:00');
        $schedule->command('subscriptions:send-expiry-emails')->dailyAt('12:00');
        $schedule->command('documents:send-reminders')->dailyAt('12:00');
        $schedule->command('document:check-participants')->everyMinute();
        $schedule->command('check:first-transaction-monthly')->monthly();

        Log::info('running cron');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
