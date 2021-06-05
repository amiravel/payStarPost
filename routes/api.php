<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
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

    Route::prefix('/posts')->group(function (){

        Route::get('/index', [PostController::class, 'index'])->name('posts.index');
        Route::post('/store', [PostController::class, 'store'])->name('posts.store');
        Route::patch('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    });

});
