<?php

namespace App\Jobs;

use App\CustomClasses\Excel;
use App\CustomClasses\CategoryBuilder;
use App\CustomClasses\File;
use App\CustomClasses\FTP;
use App\Mail\ProcessingDone;
use App\Notifications\Done;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Processxlsx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Notifiable;
    protected $file;
    protected $filename;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $ftp_basePath = "categories";
        $files = FTP::getAllFiles($ftp_basePath);
        $this->filename = File::getRecentURL($files,$ftp_basePath);
        $this->file = FTP::buildFTPUrl($this->filename);
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rows = Excel::getRows($this->file);

        //Slice the first row as it contains category numbers.
        $rows = array_slice($rows, 1);
        foreach ($rows as $row) {
            $rowData = Excel::getRowData($row);
            foreach ($rowData as $data) {
                CategoryBuilder::insert($data["content"], $data["parentText"]);
            }
        }
        $this->done();
    }

    public function done(){
        //Mail::to(env("MAIL_TO"))->queue(new ProcessingDone($this->filename));
        Notification::route('mail',env('MAIL_TO'))
        ->notify(new Done($this->filename));
    }
}
