<?php

use App\Http\Controllers\MyFatoorahController;
use http\Env\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MyFatoorahController::class, 'index']);

Route::get('/', function () {
    echo route('myfatoorah.callback');
});
