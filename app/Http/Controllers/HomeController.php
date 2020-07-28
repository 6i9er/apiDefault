<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\User;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        return "aaaaaaaaaaaaaaaa    ";
        $data = [
            'name' => 'mina amir',
            'email' => 'eng.mina23@gmail.com',
            'subject' => 'Subscribtion Complete'
            ];

        $email = new ThanksSubscribeMail($data);
        $email->setData($data) ;
        Mail::to($data['email'])->send($email);

        return "email Has Send Success";
    }

    public function listUsers(Request $request){


    }
}
