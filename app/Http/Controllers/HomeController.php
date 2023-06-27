<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        
    }

    public function userHome(){
        return view ('home', ['msg' =>"User Role"]);
    }

    public function kasirHome(){
        return view ('home', ['msg' =>"Kasir Role"]);
    }

    public function moHome(){
        return view ('home', ['msg' =>"Manajemen Operasi Role"]);
    }
}
