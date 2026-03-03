<?php

Route::get('/hallo', function () {
    return "Die Web.php wird gelesen!";
});

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Auth-System (MUSS vor dem Redirect stehen)
Auth::routes();

// 3. Sicherheits-Redirect
Route::redirect('/home', '/dashboard');

// 4. Geschützter Bereich
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');

    // Mitarbeiter-Verwaltung (Einheitliche Schreibweise!)
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->middleware('auth');
    Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->middleware('auth');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->middleware('auth');
    Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    // Bearbeiten (Formular anzeigen)
    Route::get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit'])->middleware('auth');

    // Update (Speichern der Änderungen)
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->middleware('auth');

    // Löschen
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy'])->middleware('auth');

    // Debug-Test
    Route::get('/debug-test', function () {
        return 'Routen werden geladen!';
    })->name('debug.test');
});

//5. Positionen Bezeichnungen
Route::get('/admin/positions', [PositionController::class, 'index'])->middleware('auth');
Route::post('/admin/positions', [PositionController::class, 'store'])->middleware('auth');
Route::delete('/admin/positions/{id}', [PositionController::class, 'destroy'])->middleware('auth');