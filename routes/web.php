<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessengerController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/webhook', [MessengerController::class, 'verifyWebhook']);
Route::post('/webhook', [MessengerController::class, 'handleMessage'])->withoutMiddleware([VerifyCsrfToken::class]);;

