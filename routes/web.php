<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZeiteintragController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/zeiteintraege/neu', [App\Http\Controllers\ZeiteintragController::class, 'create'])->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/zeiteintraege/neu', [ZeiteintragController::class, 'create']);
    Route::post('/zeiteintraege', [ZeiteintragController::class, 'store']);
});


