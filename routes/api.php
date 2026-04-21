<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchuelerController;

// Der Login-Pfad: https://signsync.de/api/login
Route::post('/login', [AuthController::class, 'login']);

// Alle Routen hier drin sind nur mit dem Schlüssel (Token) erreichbar
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Hier kommen später die Zeiteinträge für die App rein
});