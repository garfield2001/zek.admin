<?php

use App\Http\Controllers\Pages\CateringPackagesController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\ManageCateringController;
use App\Http\Controllers\Pages\ReportsController;
use App\Http\Controllers\Pages\ReservationsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('show.dashboard.page');
    Route::get('reservations', [ReservationsController::class, 'index'])->name('show.reservations.page');

    // Catering Routes
    Route::prefix('catering')->group(function () {
        Route::get('/', [ManageCateringController::class, 'index'])->name('show.catering.page');
        Route::get('/packages', [ManageCateringController::class, 'packages'])->name('show.catering.packages.page');
        Route::get('/categories', [ManageCateringController::class, 'categories'])->name('show.catering.categories.page');
        Route::get('/dishes', [ManageCateringController::class, 'dishes'])->name('show.catering.dishes.page');
    });

    Route::get('reports', [ReportsController::class, 'index'])->name('show.reports.page');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
