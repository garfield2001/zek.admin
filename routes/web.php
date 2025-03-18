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
    Route::get('catering/packages', [CateringPackagesController::class, 'index'])->name('show.catering-packages.page');
    Route::get('reports', [ReportsController::class, 'index'])->name('show.catering-packages.page');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
