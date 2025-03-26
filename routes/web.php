<?php

use App\Http\Controllers\Pages\CateringPackagesController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\ReportsController;
use App\Http\Controllers\Pages\ReservationsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('show.dashboard.page');
    Route::get('reservations', [ReservationsController::class, 'index'])->name('show.reservations.page');

    // Catering Routes
    Route::prefix('catering')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Catering');
        })->name('show.catering.page');

        Route::get('/packages', function () {
            return Inertia::render('catering/Packages');
        })->name('show.catering.packages.page');

        Route::get('/categories', function () {
            return Inertia::render('catering/Categories');
        })->name('show.catering.categories.page');

        Route::get('/dishes', function () {
            return Inertia::render('catering/Dishes');
        })->name('show.catering.dishes.page');
    });

    Route::get('reports', [ReportsController::class, 'index'])->name('show.reports.page');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
