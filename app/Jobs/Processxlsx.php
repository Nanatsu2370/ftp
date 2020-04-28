<?php

namespace App\Jobs;

use App\Category;
use App\CustomClasses\Excel;
use App\CustomClasses\Row;
use App\CustomClasses\File;
use App\CustomClasses\FTP;

use App\Notifications\Done;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        $files = FTP::getAllFiles(env('FTP_BASEPATH'));
        $this->filename = File::getRecentPath($files, env('FTP_BASEPATH'));
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

        foreach ($rows as $rowData)
        {
            $row = new Row($rowData);
            foreach($row->cells as $cellData)
            {
                $parentId = Category::calculateId($cellData['parentText']);

                $category = Category::init($cellData['content'],$parentId);
                $category->save();
            }
        }
        $this->done();
    }

    public function done(){
        Notification::route('mail',env('MAIL_TO'))
            ->notify(new Done($this->filename));
    }
}
