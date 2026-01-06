<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Főoldal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Filmek
Route::get('/filmek', [MovieController::class, 'index'])->name('movies.index');
Route::get('/filmek/{movie}', [MovieController::class, 'show'])->name('movies.show');

// Vetítések
Route::get('/vetitesek', [ScreeningController::class, 'index'])->name('screenings.index');

// Foglalás
Route::get('/foglalas/{screening}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/foglalas/{screening}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/foglalasaim/{booking}', [BookingController::class, 'show'])->name('bookings.show');

// Jegy letöltés
Route::get('/jegy/{booking}/pdf', [TicketController::class, 'downloadPdf'])->name('tickets.pdf');
Route::get('/qr/{qrCode}', [TicketController::class, 'generateQrCode'])->name('tickets.qr');


Route::middleware(['auth'])->group(function () {
    // Jegyeim oldal - csak bejelentkezett felhasználóknak
    Route::get('/jegyeim', [TicketController::class, 'myTickets'])->name('tickets.my-tickets');
    
    // Profil kezelés
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/jelszo', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', App\Http\Middleware\CheckAdminRole::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        Route::get('/filmek', [MovieController::class, 'adminIndex'])->name('movies.index');
        Route::get('/filmek/letrehozas', [MovieController::class, 'create'])->name('movies.create');
        Route::post('/filmek', [MovieController::class, 'store'])->name('movies.store');

        Route::get('/filmek/{movie}/szerkesztes', [MovieController::class, 'edit'])->name('movies.edit');
        Route::put('/filmek/{movie}', [MovieController::class, 'update'])->name('movies.update');
        Route::delete('/filmek/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');

        Route::get('/vetitesek', [ScreeningController::class, 'adminIndex'])->name('screenings.index');
        Route::get('/vetitesek/create', [ScreeningController::class, 'create'])->name('screenings.create');
        Route::post('/vetitesek', [ScreeningController::class, 'store'])->name('screenings.store');

        Route::get('/vetitesek/{screening}/edit', [ScreeningController::class, 'edit'])->name('screenings.edit');
        Route::put('/vetitesek/{screening}', [ScreeningController::class, 'update'])->name('screenings.update');
        Route::delete('/vetitesek/{screening}', [ScreeningController::class, 'destroy'])->name('screenings.destroy');


        // Foglalások listázása
        Route::get('/foglalasok', [BookingController::class, 'adminIndex'])->name('bookings.index');

        // Foglalás részletei
        Route::get('/foglalasok/{booking}', [BookingController::class, 'adminShow'])->name('bookings.show');

        // Foglalás törlése / lemondása
        Route::patch('/foglalasok/{booking}/cancel', 
        [BookingController::class, 'destroy'])
        ->name('bookings.cancel');

    });


// Auth
require __DIR__ . '/auth.php';