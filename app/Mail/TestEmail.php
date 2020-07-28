<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct()
    {

    }

    public function setData($recivedData)
    {
        $this->data = $recivedData;
    }

    public function build()
    {
        //Sender Email
        $address = 'janeexampexample@example.com';
        //Email Title (subject )
        $subject = 'This is a demo!';
        //Sender Name
        $name = 'Jane Doe';

        return $this->view('emails.test')
            ->from($address, $name)
            ->subject($subject)
            ->with([ 'message' => $this->data]);
    }
}
