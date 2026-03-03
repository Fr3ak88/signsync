<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. Die absolute Basis-Route zum Testen
Route::get('/debug-test', function () {
    return 'Die Web-Routen Datei wird geladen!';
})->name('debug.test');

// 2. Deine Mitarbeiter-Route OHNE Gruppe und OHNE Controller-Check
// Wir nutzen hier eine einfache Funktion, um zu sehen, ob der Name registriert wird
Route::get('/admin/employees', function () {
    return 'Hier wird bald die Mitarbeiter-Liste sein.';
})->name('admin.employees.index');

// 3. Auth-Routen
Auth::routes();

// 4. Dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);