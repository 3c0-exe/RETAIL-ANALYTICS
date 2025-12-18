<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityLogController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics route
    Route::get('/analytics/sales', [\App\Http\Controllers\Analytics\SalesAnalyticsController::class, 'index'])
        ->name('analytics.sales');
    Route::post('/analytics/sales/export', [\App\Http\Controllers\Analytics\SalesAnalyticsController::class, 'export'])
        ->name('analytics.sales.export');


    // Customer Analytics Routes (ADD THESE)
    Route::get('/analytics/customers', [\App\Http\Controllers\Analytics\CustomerAnalyticsController::class, 'index'])
        ->name('analytics.customers');
    Route::get('/analytics/customers/{customer}', [\App\Http\Controllers\Analytics\CustomerAnalyticsController::class, 'show'])
        ->name('analytics.customers.show');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile routes (available to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme.update');


    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        Route::get('imports', [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('imports.index');
    Route::get('imports/create', [\App\Http\Controllers\Admin\ImportController::class, 'create'])->name('imports.create');
    Route::post('imports/upload', [\App\Http\Controllers\Admin\ImportController::class, 'upload'])->name('imports.upload');
    Route::post('imports/{import}/process', [\App\Http\Controllers\Admin\ImportController::class, 'process'])->name('imports.process');
    Route::get('imports/{import}', [\App\Http\Controllers\Admin\ImportController::class, 'show'])->name('imports.show');
    Route::delete('imports/{import}', [\App\Http\Controllers\Admin\ImportController::class, 'destroy'])->name('imports.destroy');

        // Add Activity Logs here
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

          // Add these new routes:
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });

    // Branch Manager Routes
    Route::middleware(['role:branch_manager', 'branch.access'])->prefix('branch')->name('branch.')->group(function () {
        // Branch-specific routes will go here
    });
});

require __DIR__.'/auth.php';
