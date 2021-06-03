<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterationController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', LoginController::class)->name('login');
Route::post('/register', RegisterController::class)->name('register');


Route::middleware(\App\Http\Middleware\CheckToken::class)->group(function (){

    Route::get('/hi', function (){
        return \App\Response\Response::json(200);
    });

});
