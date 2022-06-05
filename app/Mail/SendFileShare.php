<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFileShare extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileShareURL, $fileName, $hasPersonalization, $name, $company)
    {
        //
        $this->url = $fileShareURL;
        $this->filename = $fileName;
        $this->hasPersonalization = $hasPersonalization;
        $this->name = $name;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->hasPersonalization){
            if($this->name != '' && $this->company != ''){
                return $this->subject($this->name . ' from '. $this->company .' sent you a file! | ' . $this->filename)->view('file-share-email', ['url' => $this->url, 'fileName' => $this->filename]);
            }
            else{
                if($this->name != ''){
                    return $this->subject('You received a file from ' . $this->name . ' | ' . $this->filename)->view('file-share-email', ['url' => $this->url, 'fileName' => $this->filename]);
                }
                else{
                    return $this->subject('You received a file from ' . $this->company . ' | ' . $this->filename)->view('file-share-email', ['url' => $this->url, 'fileName' => $this->filename]);
                }
            }
        }else{
            return $this->subject('You received a file via DropSpace | ' . $this->filename)->view('file-share-email', ['url' => $this->url, 'fileName' => $this->filename]);
        }
    }
}
