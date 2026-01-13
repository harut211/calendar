<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/google/auth', [GoogleController::class, 'redirect']);
// Route::get('/google/callback', [GoogleController::class, 'callback']);
// Route::post('/addevent', [\App\Http\Controllers\AddEventConrolller::class, 'addEvent']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
