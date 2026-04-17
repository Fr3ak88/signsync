<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ZeiteintragController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SchuelerController;
use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\WorktimeController;
use App\Http\Controllers\ArbeitsnachweisController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MollieWebhookController;


// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Auth-System
Auth::routes();

// 3. Sicherheits-Redirect
Route::get('/home', function () {
    if (!Auth::check()) return redirect('/login');
    
    $role = Auth::user()->role;
    if ($role === 'superadmin') return redirect()->route('superadmin.index');
    
    return redirect()->route('dashboard');
});

// --- Öffentliche Seiten ---
Route::get('/impressum', function () { return view('impressum'); })->name('impressum');
Route::get('/datenschutz', function () { return view('datenschutz'); })->name('datenschutz');
Route::get('/preise', [SubscriptionController::class, 'index'])->name('plans.index');
Route::post('/webhooks/mollie', [App\Http\Controllers\MollieWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhooks.mollie');
Route::view('/avv', 'legal.avv')->name('avv');

// 4. Geschützter Bereich (Muss eingeloggt sein)
Route::middleware(['auth'])->group(function () {

    // --- ABO SPEICHERN ---
    // Muss außerhalb von 'has.plan' stehen, damit der Admin sein Paket wählen kann!
    Route::post('/select-plan', [SubscriptionController::class, 'storePlan'])->name('plans.store');

    // --- INTERNER BEREICH (Abo erforderlich) ---
    Route::middleware(['has.plan'])->group(function () {
    
    // Allgemeines Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Heartbeat für Session-Verlängerung
    Route::get('/heartbeat', function () {
        session()->put('last_activity', now());
        return response()->json(['status' => 'ok']);
    })->name('heartbeat');

    // --- MITARBEITER BEREICH (Zeiteinträge) ---
    Route::prefix('zeiteintraege')->group(function () {
        Route::get('/', [ZeiteintragController::class, 'index'])->name('zeiteintraege.index');
        Route::get('/export', [ZeiteintragController::class, 'exportPdf'])->name('zeiteintraege.export');
        Route::post('/sign', [ZeiteintragController::class, 'signMonth'])->name('zeiteintraege.sign');
        Route::get('/create', [ZeiteintragController::class, 'create'])->name('zeiteintraege.create');
        Route::post('/', [ZeiteintragController::class, 'store'])->name('zeiteintraege.store');
        Route::get('/{id}/edit', [ZeiteintragController::class, 'edit'])->name('zeiteintraege.edit');
        Route::put('/{id}', [ZeiteintragController::class, 'update'])->name('zeiteintraege.update');
        Route::get('/{id}', [ZeiteintragController::class, 'show'])->name('zeiteintraege.show');
        Route::delete('/{id}', [ZeiteintragController::class, 'destroy'])->name('zeiteintraege.destroy');
    });

    Route::get('/worktime', [WorktimeController::class, 'index'])->name('worktime.index');
    Route::get('/worktime/create', [WorktimeController::class, 'create'])->name('worktime.create');
    Route::post('/worktime', [WorktimeController::class, 'store'])->name('worktime.store');
    Route::post('/worktime/export', [WorktimeController::class, 'exportPdf'])->name('worktime.export');

    Route::get('/mein-archiv', [WorktimeController::class, 'archiv'])->name('monatsabschluss.archiv');

    // --- FIRMEN-ADMIN BEREICH ---
    // Hier lag der Fehler: 'admin' Prefix war doppelt gemoppelt
    Route::prefix('admin')->group(function () {
        // Zentrale Überwachung der Arbeitsnachweise (Offen vs. Abgeschlossen)
        Route::get('/arbeitsnachweise', [ArbeitsnachweisController::class, 'index'])->name('admin.arbeitsnachweise.index');
        Route::get('/admin/arbeitsnachweise/details/{user}', [ArbeitsnachweisController::class, 'show'])->name('admin.arbeitsnachweise.show');

        // Mitarbeiter Verwaltung
        Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
        Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('admin.employees.restore');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

        // Positionen
        Route::get('/positions', [PositionController::class, 'index'])->name('admin.positions.index');
        Route::post('/positions', [PositionController::class, 'store'])->name('admin.positions.store');
        Route::delete('/positions/{id}', [PositionController::class, 'destroy'])->name('admin.positions.destroy');

        // Schüler
        Route::get('/schueler', [SchuelerController::class, 'index'])->name('admin.schueler.index');
        Route::get('/schueler/create', [SchuelerController::class, 'create'])->name('admin.schueler.create');
        Route::post('/schueler', [SchuelerController::class, 'store'])->name('admin.schueler.store');
        Route::get('/schueler/{id}/edit', [SchuelerController::class, 'edit'])->name('admin.schueler.edit');
        Route::put('/schueler/{id}', [SchuelerController::class, 'update'])->name('admin.schueler.update');
        Route::delete('/schueler/{id}', [SchuelerController::class, 'destroy'])->name('admin.schueler.destroy');

        // Archiv (Korrigiert: Kein doppeltes 'admin' mehr)
        Route::get('/archive', [ArchiveController::class, 'index'])->name('admin.archive.index');
        Route::get('/archive/download/{id}', [ArchiveController::class, 'download'])->name('admin.archive.download');
        Route::post('/archive/{id}/cancel', [App\Http\Controllers\ArbeitsnachweisController::class, 'cancel'])->name('admin.archive.cancel');

        // ABO
        Route::post('/select-plan', [SubscriptionController::class, 'storePlan'])->name('plans.store');
        Route::post('/cancel-subscription', [SubscriptionController::class, 'cancel'])->name('plans.cancel');

        // AVV
        Route::get('/admin/avv', [LegalController::class, 'showAvv'])->name('admin.avv.show');
        Route::post('/admin/avv/accept', [LegalController::class, 'acceptAvv'])->name('admin.avv.accept');
    });

    }); // Ende 'has.plan'

    // --- SUPER-ADMIN BEREICH ---
    Route::middleware(['is_superadmin'])->prefix('superadmin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.index');
        Route::get('/stats', [SuperAdminController::class, 'stats'])->name('superadmin.stats');

        // User Managment
        Route::get('/users', [SuperAdminController::class, 'users'])->name('superadmin.users.index');
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.users.edit');
        Route::post('/users/{user}/resend', [SuperAdminController::class, 'resendInvitation'])->name('superadmin.users.resend');
        Route::put('/users/{id}', [SuperAdminController::class, 'update'])->name('superadmin.users.update');
        Route::delete('/users/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.users.destroy');

        //Paln manuell aktuallisieren
        Route::post('/users/{user}/update-plan', [SuperAdminController::class, 'updatePlan'])->name('superadmin.users.updatePlan');
        Route::post('/users/{user}/resend', [SuperAdminController::class, 'resendInvitation'])->name('superadmin.users.resend');

        // Firmen Management
        Route::get('/firmen', [SuperAdminController::class, 'firmen'])->name('superadmin.firmen');
        Route::delete('/firmen/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.firmen.destroy');
    });

}); // Ende Middleware 'auth'



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