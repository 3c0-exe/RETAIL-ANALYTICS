<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Notification API routes (using web middleware for session auth)
Route::middleware(['web', 'auth'])->prefix('notifications')->group(function () {
    Route::get('/recent', [NotificationApiController::class, 'recent']);
    Route::post('/{id}/read', [NotificationApiController::class, 'markAsRead']);
    Route::post('/mark-all-read', [NotificationApiController::class, 'markAllAsRead']);
});
