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
        Commands\LoanRepay::class,
        Commands\ProductionClosingStockManager::class,
        Commands\ProductionOpeningStockManager::class,
        Commands\ProductionCheckExpiredItems::class,
        Commands\ActivateSuspendedEmployees::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('loan:repay')->dailyAt('08:00');
        $schedule->command('manufacturing:closing-stock-manager')->dailyAt('23:59');
        $schedule->command('manufacturing:opening-stock-manager')->dailyAt('00:00');
        $schedule->command('manufacturing:check-expired-items')->dailyAt('23:59');
        $schedule->command('disciplinary:activate-employee')->dailyAt('00:00');
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
