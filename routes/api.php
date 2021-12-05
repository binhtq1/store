<?php

use App\Http\Controllers\Api\BarController;
use App\Http\Controllers\Api\QRController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'qr-code', 'as' => 'qr-code.'], function () {
    Route::get('/{code}', [QRController::class, 'show'])->name('show');
});
Route::group(['prefix' => 'bar-code', 'as' => 'bar-code.'], function () {
    Route::get('/{code}', [BarController::class, 'show'])->name('show');
});
