<?php

namespace App\Http\Controllers;

use App\Events\odcastrocessed;
use App\Mail\test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        
        event(new odcastrocessed(request()->user()->email));

        return view('home',['title'=>'Dashboard']);
    }
}
