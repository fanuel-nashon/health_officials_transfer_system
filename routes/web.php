<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transfers
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::get('/transfers/create', [TransferController::class, 'create'])
        ->middleware('role:nurse_doctor')->name('transfers.create');
    Route::post('/transfers', [TransferController::class, 'store'])
        ->middleware('role:nurse_doctor')->name('transfers.store');
    Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show');
    Route::get('/transfers/{transfer}/review', [TransferController::class, 'review'])
        ->middleware('role:facility_admin|district_officer|region_officer|ministry_official|admin')
        ->name('transfers.review');
    Route::post('/transfers/{transfer}/review', [TransferController::class, 'processReview'])
        ->middleware('role:facility_admin|district_officer|region_officer|ministry_official|admin')
        ->name('transfers.process-review');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:view_reports')
        ->name('reports.index');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::post('/admin/users/{user}/assign-role', [AdminController::class, 'assignRole'])->name('admin.users.assign-role');

        Route::resource('facilities', FacilityController::class);
        Route::resource('locations', LocationController::class);
    });

    // Notifications
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
