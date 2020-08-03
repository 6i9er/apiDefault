<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendForgetPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

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
        $address = env('MAIL_FROM_ADDRESS');
        //Sender Name
        $name =  env('MAIL_FROM_NAME');
        //Email Title (subject )
        $subject = 'bikers Reset Password';
        // View For Email
        return $this->view('emails.sendForgetPasswordLink')
            ->from($address, $name)
            ->subject($subject)
            ->with([ 'data' => $this->data]);
    }
}
