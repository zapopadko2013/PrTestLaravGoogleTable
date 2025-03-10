<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Revolution\Google\Sheets\Facades\Sheets;

class MyProgressBarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   // protected $signature = 'my:progressbar';
   // protected $description = 'A command that shows a progress bar';

   protected $signature = 'app:fetchread {count}';

   public function readfile(){
    $jsonData = file_get_contents(storage_path('app/public/nastr.json'));
    $data = json_decode($jsonData);
    return $data->SOHRANGOGLE;
}

    public function handle()
    {


        $output = new ConsoleOutput();
        $output->writeln($this->readfile());




       $sh = Sheets::spreadsheet($this->readfile())->sheet('Лист1')->get();

   $header = $sh->pull(0);
   $values = Sheets::collection(header: $header, rows: $sh);
   if ($this->argument('count')!=null && $this->argument('count')>0)
   $values = $values->take($this->argument('count'));






       $sheets=$values->toArray();
       $v1=count($sheets);

      // $output->writeln(json_encode($sheets));

       $output->writeln($v1);

      // $output->writeln($this->argument('count'));

      $icount=$this->argument('count');




      $progressbar1 = $this->output->createProgressBar($v1);

       $progressbar1->start();





        $i1=0;


        foreach ($sheets  as $sheet) {

           if ($icount >0 && $icount==$i1) {
             break;

           }


           $i1++;
           $progressbar1->advance();
           $output->writeln(' ');

           $output->writeln('Запись- '.$i1.'; ID- '.$sheet["id"].'; Name-'.$sheet["name"].'; Status-'.$sheet["status"].'; Comment-'.$sheet["comment"].';');

       }



       $progressbar1->finish();





    }


}
