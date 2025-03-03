<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Eloquent;
//use SheetDB\SheetDB;
//use GuzzleHttp\Client;
use App\Http\Controllers\EloquentController;
use Revolution\Google\Sheets\Facades\Sheets;
//use Google_Client;
//use Google\Client;
//use App\Services\GoogleSheetsService;



//use Google_Service_Sheets;

class Kernel extends ConsoleKernel
{

    public function readfile(){
        $jsonData = file_get_contents(storage_path('app/public/nastr.json'));
        $data = json_decode($jsonData);
        return $data->SOHRANGOGLE;
    }
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {



                   info("run tasks");

                   $schedule->command('app:update-time')->everyMinute();


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
