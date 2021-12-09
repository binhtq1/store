<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::group(['prefix' => 'generate', 'as' => 'generate.'], function () {
    Route::get('/qr-code', [HomeController::class, 'generateQRCode'])->name('qr-code');
    Route::get('/bar-code', [HomeController::class, 'generateBarCode'])->name('bar-code');
});


