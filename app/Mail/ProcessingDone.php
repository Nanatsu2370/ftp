<?php

namespace App\Mail;

use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProcessingDone extends Mailable
{
    use Queueable, SerializesModels;
    protected $filename;
    /**
     * Create a new message instance.
     * @param string $filename File's name to be processed. Needed for the message body.
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename=$filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->html(sprintf("%s dosyası işlendi", $this->filename))
            ->attachData(Category::dump(),'current.json');
    }
}
