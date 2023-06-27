<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function check(Request $request){
       $input = $request->all();

       $this->validate($request, [
        'email'=>'required|email',
        'password'=>'required'
       ]);
       if(auth()->attempt(['email' => $input["email"], 'password' => $input["password"]])){
        if(auth()->user()->role == 'kasir'){
            return redirect()->route('home.kasir');
        }
        else if(auth()->user()->role == 'mo'){
            return redirect()->route('home.mo');
        }else{
            return redirect()->route('home');
        }
       }else{
        return redirect()
        ->route("login")
        ->with("error", 'Inccorect email or password');
       }
    }
}
