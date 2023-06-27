<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'user-role::user'])->group(function(){
    Route::get("/home", [HomeController::class, 'userHome'])->name('home');
});

Route::middleware(['auth', 'user-role::kasir'])->group(function(){
    Route::get("/kasir/home", [HomeController::class, 'kasirHome'])->name('home.kasir');
});


Route::middleware(['auth', 'user-role::mo'])->group(function(){
    Route::get("/mo/home", [HomeController::class, 'moHome'])->name('home.mo');
});

