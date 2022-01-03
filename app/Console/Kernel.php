<?php

namespace App\Console;

use App\Console\Commands\BannedRestaurant;
use App\Console\Commands\DebtorRestaurant;
use App\Console\Commands\ExpiredOffers;
use App\Console\Commands\RestaurantBusy;
use App\Console\Commands\RestaurantFree;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        // $schedule->command(RestaurantBusy::class)->everyMinute();
        // $schedule->command(DebtorRestaurant::class)->everyMinute();
        $schedule->command(BannedRestaurant::class)->everyMinute();
        // $schedule->command(RestaurantFree::class)->everyMinute();
        // $schedule->command(ExpiredOffers::class)->everyMinute();
        
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
