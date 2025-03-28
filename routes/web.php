<?php

use App\Http\Controllers\Pages\CateringController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\ManageCateringController;
use App\Http\Controllers\Pages\ReportsController;
use App\Http\Controllers\Pages\ReservationsController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('show.dashboard.page');


    // Reservations Routes
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationsController::class, 'index'])->name('show.reservations.page');
    });

    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('show.reports.page');
    });

    // Catering Routes
    Route::prefix('catering')->group(function () {
        // Dashboard
        Route::get('/', [CateringController::class, 'index'])->name('show.catering.page');

        // Reservations
        Route::prefix('reservations')->group(function () {
            Route::get('/', [ReservationsController::class, 'index'])->name('show.reservations.page');
            Route::get('/new', [ReservationsController::class, 'create'])->name('reservations.create');
            Route::get('/calendar', [ReservationsController::class, 'calendar'])->name('reservations.calendar');
        });

        // Packages
        Route::prefix('packages')->group(function () {
            Route::get('/', [CateringController::class, 'packages'])->name('show.catering.packages.page');
            Route::get('/new', [CateringController::class, 'createPackage'])->name('catering.packages.create');
            Route::put('/{package}', [CateringController::class, 'updatePackage'])->name('catering.packages.update');
            Route::delete('/{package}', [CateringController::class, 'deletePackage'])->name('catering.packages.delete');
        });

        // Menu
        Route::prefix('menu')->group(function () {
            Route::get('/categories', [CateringController::class, 'categories'])->name('catering.menu.categories');
            Route::get('/dishes', [CateringController::class, 'dishes'])->name('catering.menu.dishes');
        });

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('show.reports.page');
            Route::get('/reservations', [ReportsController::class, 'reservations'])->name('reports.reservations');
            Route::get('/revenue', [ReportsController::class, 'revenue'])->name('reports.revenue');
        });

        // Settings
        Route::prefix('settings')->group(function () {
            Route::get('/', [CateringController::class, 'settings'])->name('catering.settings');
            Route::get('/profile', [CateringController::class, 'profile'])->name('catering.settings.profile');
            Route::get('/system', [CateringController::class, 'system'])->name('catering.settings.system');
            Route::put('/minimum-guests', [CateringController::class, 'updateMinimumPersons'])
                ->name('catering.settings.minimum-guests');
        });
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
