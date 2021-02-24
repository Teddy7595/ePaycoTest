<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

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

Route::prefix('wallet')->group(function () 
{
    Route::post('/signin', [ClientController::class, 'signin']);
    Route::post('/status', [ClientController::class, 'status']);
    Route::post('/recharge', [ClientController::class, 'recharge']);
    Route::post('/payment', [ClientController::class, 'recharge']);
    Route::post('/confirm', [ClientController::class, 'recharge']);
});


