<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

use App\Console\Commands\CreateDailyForExRate;
use App\Console\Commands\CreateLogoutForPlenaryAttendance;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateDailyForExRate::class,
        CreateLogoutForPlenaryAttendance::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dailyForExRate:create');
            // ->daily();
            // ;
        $schedule->command('plenaryAttendance:createLogout');
    }
}
