<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\AlertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityLogController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics Routes
    Route::get('/analytics/sales', [\App\Http\Controllers\Analytics\SalesAnalyticsController::class, 'index'])
        ->name('analytics.sales');

    // Heatmap detail endpoint
    Route::get('/analytics/sales/heatmap-detail', [\App\Http\Controllers\Analytics\SalesAnalyticsController::class, 'getHeatmapDetail'])
        ->name('analytics.sales.heatmap-detail');

    // Customer Analytics Routes
    Route::get('/analytics/customers', [\App\Http\Controllers\Analytics\CustomerAnalyticsController::class, 'index'])
        ->name('analytics.customers');
    Route::get('/analytics/customers/{customer}', [\App\Http\Controllers\Analytics\CustomerAnalyticsController::class, 'show'])
        ->name('analytics.customers.show');

    // Export Routes (Rate Limited - 10 per minute)
    Route::middleware(['throttle:exports'])->group(function () {
        Route::post('/export/sales/csv', [\App\Http\Controllers\ExportController::class, 'salesCsv'])
            ->name('export.sales.csv');
        Route::post('/export/sales/excel', [\App\Http\Controllers\ExportController::class, 'salesExcel'])
            ->name('export.sales.excel');
        Route::post('/export/sales/pdf', [\App\Http\Controllers\ExportController::class, 'salesPdf'])
            ->name('export.sales.pdf');
        Route::post('/export/customers/csv', [\App\Http\Controllers\ExportController::class, 'customersCsv'])
            ->name('export.customers.csv');
    });

    // Forecast Routes (Rate Limited - 5 regenerations per hour)
    Route::get('/forecasts', [ForecastController::class, 'index'])->name('forecasts.index');
    Route::post('/forecasts/regenerate', function() {
        \Artisan::call('forecast:generate');
        return back()->with('success', 'Forecasts regenerated successfully!');
    })->name('forecasts.regenerate')->middleware(['throttle:forecast']);

    // Alert routes
    Route::prefix('alerts')->name('alerts.')->group(function () {
        Route::get('/', [AlertController::class, 'index'])->name('index');
        Route::get('/unread', [AlertController::class, 'unread'])->name('unread');
        Route::post('/{alert}/read', [AlertController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [AlertController::class, 'markAllAsRead'])->name('readAll');
        Route::get('/{alert}', [AlertController::class, 'show'])->name('show');
    });

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    // Password update (Rate Limited - 5 per hour)
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update')
        ->middleware(['throttle:password']);

    Route::put('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes (Rate Limited - 60 per minute for imports)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Branch Management
        Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Category Management
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

        // Product Management
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

        // Import routes (Rate Limited)
        Route::get('imports', [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('imports.index');
        Route::get('imports/create', [\App\Http\Controllers\Admin\ImportController::class, 'create'])->name('imports.create');
        Route::get('imports/download-sample', [\App\Http\Controllers\Admin\ImportController::class, 'downloadSample'])->name('imports.download-sample');

        Route::middleware(['throttle:imports'])->group(function () {
            Route::post('imports/upload', [\App\Http\Controllers\Admin\ImportController::class, 'upload'])->name('imports.upload');
            Route::post('imports/{import}/process', [\App\Http\Controllers\Admin\ImportController::class, 'process'])->name('imports.process');
        });

        Route::get('imports/{import}', [\App\Http\Controllers\Admin\ImportController::class, 'show'])->name('imports.show');
        Route::get('imports/{import}/export-errors', [\App\Http\Controllers\Admin\ImportController::class, 'exportErrors'])->name('imports.export-errors');
        Route::delete('imports/{import}', [\App\Http\Controllers\Admin\ImportController::class, 'destroy'])->name('imports.destroy');

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // Branch Manager Routes
    Route::middleware(['role:branch_manager', 'branch.access'])->prefix('branch')->name('branch.')->group(function () {
        // Branch-specific routes will go here
    });
});

require __DIR__.'/auth.php';
