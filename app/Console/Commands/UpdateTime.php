<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Revolution\Google\Sheets\Facades\Sheets;
use App\Models\Eloquent;
use App\Enums\EloquentStatusEnum;

use Google\Client;
use App\Services\GoogleSheetsService;
use Google_Client;



use Google_Service_Sheets;

class UpdateTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

     public function readfile(){
        $jsonData = file_get_contents(storage_path('app/public/nastr.json'));
        $data = json_decode($jsonData);
        return $data->SOHRANGOGLE;
    }
    public function handle()
    {



       info("run tasks");





$sheets = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();



$output = new \Symfony\Component\Console\Output\ConsoleOutput();


$client = new Google_Client();

$client->setAuthConfig(storage_path('app/public/credentials.json'));


$client->addScope(Google_Service_Sheets::SPREADSHEETS);
$service = new Google_Service_Sheets($client);
$spreadsheetId = $this->readfile(); // Get spreadsheet ID from the .env file



    $eloquento=array();
    $eloquents1 = Eloquent::all();

    foreach ($eloquents1 as $eloquent1)
    {
        $eloquento[]=[
            'id' => $eloquent1->id,
            'name' => $eloquent1->name,
            'status' => $eloquent1->status->value,
        ];


    }

    //////

    ///Сравненние массивов
    $eloquentdobav=array();
    $eloquentupdate=array();




    foreach ($eloquento as $eloquent) {


       $fl1=false;

           foreach ($sheets as $key => $sheet) {

           if($sheet[0] == $eloquent["id"]) {

               $rowIndex=$key;

              if ($sheet[1] != $eloquent["name"]||$sheet[2] != $eloquent["status"]) {

               $output->writeln("row-".json_encode($sheet));
               $output->writeln("key-".$key);

               if ($eloquent["status"]=='Prohibited') {






   $range = "Лист1!A{$rowIndex}:Z{$rowIndex}"; // Specify the range of the row to delete (adjust column range as needed)

   // Retrieve the current data in the row (optional)
   $response = $service->spreadsheets_values->get($spreadsheetId, $range);

   // If the row exists, delete it
   if ($response->getValues()) {
       $requests = [
           new \Google_Service_Sheets_Request([
               'deleteDimension' => [
                   'range' => [
                       'sheetId' => 0, // You might need to get the sheet ID dynamically if not the first sheet
                       'dimension' => 'ROWS',
                       'startIndex' => $rowIndex , // 0-based index for rows
                       'endIndex' => $rowIndex+1, // The end index of the row to delete
                   ]
               ]
           ])
       ];

       $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
           'requests' => $requests
       ]);





       $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);


   }

                //////////





               } else {

                   $output->writeln("row-".json_encode($sheet));
                   $output->writeln("key-".$key);


                   Sheets::spreadsheet($this->readfile())->sheet('Лист1')->range('A'.$rowIndex+1)->update([[$eloquent["id"], $eloquent["name"], $eloquent["status"]]]);


                   $eloquentupdate[]=[
                       'id' => $eloquent["id"],
                       'name' => $eloquent["name"],
                       'status' => $eloquent["status"],
                    ];
               }



              }

               $fl1=true;
               break;
           }
       }
       if ($fl1) {



       } else {


           if ($eloquent["status"]=='Allowed') {

               $output->writeln("row-".json_encode($sheet));
               $output->writeln("key-".$key);

           $eloquentdobav[]=[
               'id' => $eloquent["id"],
               'name' => $eloquent["name"],
               'status' => $eloquent["status"],
            ];

           }

       }



    }

    /////

    if (count($eloquentupdate)>0) {




   }

    /////

    ////////
   if (count($eloquentdobav)>0) {
       Sheets::spreadsheet($this->readfile())->sheet('Лист1')->append($eloquentdobav);


}
    ///////


       ////////////////
       ////Удаление лишнего в таблице
       $output->writeln("Удаление лишнего в таблице");


        foreach ($sheets as $key => $sheet) {

        $fl1=false;
        $rowIndex=-2;
           foreach ($eloquento as $eloquent) {

               if($sheet[0]== $eloquent["id"]) {

                   $fl1=true;
                   break;

               }

           }

           if ($fl1) {

               //$output->writeln("Есть id");

           } else {
              $output->writeln("Нет id");

              $output->writeln($sheet[0]);

              $rowIndex=$key;




           if ($rowIndex>0) {

            $range = "Лист1!A{$rowIndex}:Z{$rowIndex}"; // Specify the range of the row to delete (adjust column range as needed)

             // Retrieve the current data in the row (optional)
             $response = $service->spreadsheets_values->get($spreadsheetId, $range);

             // If the row exists, delete it
             if ($response->getValues()) {
                 $requests = [
                     new \Google_Service_Sheets_Request([
                         'deleteDimension' => [
                             'range' => [
                                 'sheetId' => 0, // You might need to get the sheet ID dynamically if not the first sheet
                                 'dimension' => 'ROWS',
                                 'startIndex' => $rowIndex , // 0-based index for rows
                                 'endIndex' => $rowIndex+1, // The end index of the row to delete
                             ]
                         ]
                     ])
                 ];

                 $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                     'requests' => $requests
                 ]);

                 $output->writeln("spreadsheetId-".$spreadsheetId);
                 $output->writeln("batchUpdateRequest-".json_encode($batchUpdateRequest));

                 $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
             }

            }

                          //////////


           }


       }




    }
}
