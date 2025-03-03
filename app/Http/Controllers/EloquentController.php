<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent;
use App\Enums\EloquentStatusEnum;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;


use Revolution\Google\Sheets\Facades\Sheets;

use Artisan;
use App\Services\GoogleSheet;
use Google_Client;




use Google_Service_Sheets;
use Google_Service_Sheets_UpdateValuesResponse;


use Exception;




class EloquentController extends Controller
{



    private $client;
    private $service;











    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eloquents = Eloquent::all();

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln(json_encode($eloquents));


        return view('eloquents.index', compact('eloquents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'status' => ['required', Rule::enum(EloquentStatusEnum::class)],
        ]);




        $eloquent =Eloquent::create($request->all());

        if ($eloquent->status->value=='Allowed') {


            $f22=$eloquent->status->value;
            Sheets::spreadsheet($this->readfile())->sheet('Лист1')->append([[
                'id' => $eloquent->id,
                'name' => $eloquent->name,
                'status' => $f22,'comment'=>""]
            ]);

        }

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("insert");
        $output->writeln($eloquent->id);
        $output->writeln($eloquent->status->value);

        return redirect()->route('eloquents.index')
            ->with('success', 'Eloquent created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'status' => ['required', Rule::enum(EloquentStatusEnum::class)],
        ]);

        $eloquent = Eloquent::find($id);
        $eloquent->update($request->all());



        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $searchValue=$id;
        $rownum=-2;
        $sheets = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();

    foreach ($sheets as $key => $row) {

            if ($searchValue==$row[0]) {

            $output->writeln("row-".json_encode($row));
            $output->writeln("key-".$key);
            $rownum=$key+1;

           break;
        }
    }

        if ($eloquent->status->value=='Allowed') {

            $output->writeln("Allowed");







        if ($rownum>=0)
        Sheets::spreadsheet($this->readfile())->sheet('Лист1')->range('A'.$rownum)->update([[$eloquent->id, $eloquent->name, $eloquent->status]]);
        else
        Sheets::spreadsheet($this->readfile())->sheet('Лист1')->append([[$eloquent->id, $eloquent->name, $eloquent->status]]);




        } else {
            $output->writeln("delete");

            $client = new Google_Client();
        $client->setAuthConfig(storage_path('../storage/credentials.json'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);
        $spreadsheetId = $this->readfile(); // Get spreadsheet ID from the .env file
        $r1=$rownum-1;
        $range = "Лист1!A{$r1}:Z{$r1}"; // Specify the range of the row to delete (adjust column range as needed)

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
                            'startIndex' => $rownum-1 , // 0-based index for rows
                            'endIndex' =>$rownum, // The end index of the row to delete
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



        return redirect()->route('eloquents.index')
            ->with('success', 'Eloquent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    public function readfile(){
        $jsonData = file_get_contents(storage_path('app/public/nastr.json'));
        $data = json_decode($jsonData);
        return $data->SOHRANGOGLE;
    }



    public function sohrgogl (Request $request)
    {



        $data = [
            "SOHRANGOGLE" => $request->sohrangogl
        ];

         Storage::disk('public')->put('nastr.json', json_encode($data));


        $jsonData = file_get_contents(storage_path('app/public/nastr.json'));
        $data = json_decode($jsonData, true);


        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
       // $output->writeln("<info>my message</info>");
       // $output->writeln($jsonData);
      //  $output->writeln($data);




        return redirect()->route('eloquents.index')
            ->with('success', 'Eloquent sohrangogl save successfully.');

    }

    public function fetchcountreg(Request $request)
    {

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $id=$request->fetchcountreg;
        if ($id==null)
        $id=0;
        $output->writeln($id);

        Artisan::call('app:fetchread '.$id);

        $sh = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();

        $header = $sh->pull(0);
        $values = Sheets::collection(header: $header, rows: $sh);
        if ($id!=null && $id>0)
        $values = $values->take($id);

        $arraygoogls=[];
    foreach ($values->toArray() as $val) {

        $arraygoogl='{"id" :'. $val['id'].',"name" :"'. $val['name'].'","status" : "'.$val['status'].'","comment" : "'.$val['comment'].'"}';
        //$output->writeln($arraygoogl);


        $arraygoogls[]=json_decode($arraygoogl);


    }

        $eloquents=$arraygoogls;

        return view('eloquents.index', compact('eloquents'));

    }

    public function fetchcount($id)
    {


        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln($id);

        Artisan::call('app:fetchread '.$id);


        $sh = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();

        $header = $sh->pull(0);
        $values = Sheets::collection(header: $header, rows: $sh);
        if ($id!=null && $id>0)
        $values = $values->take($id);

        $arraygoogls=[];
    foreach ($values->toArray() as $val) {

        $arraygoogl='{"id" :'. $val['id'].',"name" :"'. $val['name'].'","status" : "'.$val['status'].'","comment" : "'.$val['comment'].'"}';
      //  $output->writeln($arraygoogl);


        $arraygoogls[]=json_decode($arraygoogl);


    }

        $eloquents=$arraygoogls;




        return view('eloquents.index', compact('eloquents'));


    }

    public function fetch()
	{


        Artisan::call('app:fetchread 0');

        $sheetName = "n1";



       $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $output->writeln(config('google.post_spreadsheet_id'));



     //   $output->writeln("json_encode");





    $sh = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();

    $header = $sh->pull(0);
    $values = Sheets::collection(header: $header, rows: $sh);


    $f2=[];
    foreach ($values->toArray() as $val) {

        $r1='{"id" :'. $val['id'].',"name" :"'. $val['name'].'","status" : "'.$val['status'].'","comment" : "'.$val['comment'].'"}';
       // $output->writeln($r1);


        $f2[]=json_decode($r1);



    }




       $eloquents=$f2;




        return view('eloquents.index', compact('eloquents'));


    }

	public function sozdanall()
	{
		$eloquent=array();
		for ($i=1;$i<=1000;$i++) {

        $f1='Prohibited';
        $i2=random_int(0,1);
		if ($i2==0) {
			$f1='Allowed';
		}

		$eloquent[]=[
            'name' => 'Наименование '.$i,
            'status' => $f1,
        ];


        }


		Eloquent::insert($eloquent);


        ///////
      try {
        ///////

        /////


        $eloquento=array([
            'id' => "id",
            'name' => "name",
            'status' => "status",
            'comment' => "comment"
        ]);

        $eloquents1 = Eloquent::where('status', 'Allowed')->get();
        foreach ($eloquents1 as $eloquent1)
        {
            $eloquento[]=[
                'id' => $eloquent1->id,
                'name' => $eloquent1->name,
                'status' => $eloquent1->status->value,
                'comment' => ''
            ];


        }

        Sheets::spreadsheet($this->readfile())->sheet('Лист1')->append($eloquento);


        ////////
    } catch (Exception $e) {

        Log::error($e);
        return response()->json([
            'error' => $e->getMessage(),

        ], 404);

    }
    /////////

        return redirect()->route('eloquents.index')
            ->with('success', 'Eloquent created successfully.');

	}

    public function deleteall()
	{



		Eloquent::truncate();

         ///////
      try {
        ///////


       Sheets::spreadsheet($this->readfile())->sheet('Лист1')->clear();



       ////////
    } catch (Exception $e) {

        Log::error($e);
        return response()->json([
            'error' => $e->getMessage(),

        ], 404);

    }
    /////////

        return redirect()->route('eloquents.index')
            ->with('success', 'Eloquent created successfully.');


	}

    public function destroy($id)
    {

        $eloquent = Eloquent::find($id);
        $eloquent->delete();

      ///////
      try {
      ///////

      $output = new \Symfony\Component\Console\Output\ConsoleOutput();
      $searchValue=$id;
      $rowIndex=-2;
        $sheets = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();

    foreach ($sheets as $key => $row) {

            if ($searchValue==$row[0]) {

            $output->writeln("row-".json_encode($row));
            $output->writeln("key-".$key);

            $rowIndex=$key;

           break;
        }
    }


      $client = new Google_Client();
        $client->setAuthConfig(storage_path('../storage/credentials.json'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);
        $spreadsheetId = $this->readfile(); // Get spreadsheet ID from the .env file
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

        ////////
         } catch (Exception $e) {

            Log::error($e);
            return response()->json([
                'error' => $e->getMessage(),

            ], 404);

        }
        /////////



        return redirect()->route('eloquents.index')
            ->with('success', 'Eloquent deleted successfully');
    }



    // routes functions
    /**
     * Show the form for creating a new eloquent.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('eloquents.create');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $eloquent = Eloquent::find($id);

        return view('eloquents.show', compact('eloquent'));
    }

    /**
     * Show the form for editing the specified eloquent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eloquent = Eloquent::find($id);

        return view('eloquents.edit', compact('eloquent'));
    }
}
