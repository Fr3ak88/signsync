<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ZeiteintragController; // Musst du gleich noch erstellen

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Auth-System
Auth::routes();

// 3. Sicherheits-Redirect
Route::redirect('/home', '/dashboard');

// 4. Geschützter Bereich (Nur für angemeldete Nutzer)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // --- ADMIN BEREICH (Mitarbeiter & Positionen) ---
    Route::prefix('admin')->group(function () {
        // Mitarbeiter
        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

        // Positionen
        Route::get('/positions', [PositionController::class, 'index']);
        Route::post('/positions', [PositionController::class, 'store']);
        Route::delete('/positions/{id}', [PositionController::class, 'destroy']);
    });

    // --- MITARBEITER BEREICH (Zeiteinträge) ---
    // Hier sind die Routen für den neuen Ordner "zeiteintraege"
    Route::get('/zeiteintraege', [ZeiteintragController::class, 'index'])->name('zeiteintraege.index');
    Route::get('/zeiteintraege/create', [ZeiteintragController::class, 'create'])->name('zeiteintraege.create');
    Route::post('/zeiteintraege', [ZeiteintragController::class, 'store'])->name('zeiteintraege.store');
    Route::delete('/zeiteintraege/{id}', [ZeiteintragController::class, 'destroy'])->name('zeiteintraege.destroy');

});

// Debug-Test
Route::get('/hallo', function () {
    return "Die Web.php wird gelesen!";
});
