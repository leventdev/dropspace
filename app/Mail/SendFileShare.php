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
    public function __construct($fileShareURL, $fileName)
    {
        //
        $this->url = $fileShareURL;
        $this->filename = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You received a file via DropSpace | ' . $this->filename)->view('file-share-email', ['url' => $this->url, 'fileName' => $this->filename]);
    }
}
