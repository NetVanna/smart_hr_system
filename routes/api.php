<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TelegramController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/password/email', [\App\Http\Controllers\Api\PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [\App\Http\Controllers\Api\PasswordResetController::class, 'reset']);
Route::post('/telegram/webhook', [TelegramController::class, 'handle']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return clone $request->user();
    });
    Route::post('/user/update', [AuthController::class, 'updateProfile']);

    Route::post('/fcm-token', [AuthController::class, 'updateFcmToken']);

    Route::post('/attendance/scan', [\App\Http\Controllers\Api\AttendanceController::class, 'scanQr']);
    Route::get('/attendance/my', [\App\Http\Controllers\Api\AttendanceController::class, 'myAttendance']);

    // Leaves
    Route::get('/leaves', [\App\Http\Controllers\Api\LeaveController::class, 'index']);
    Route::post('/leaves', [\App\Http\Controllers\Api\LeaveController::class, 'store']);

    // Payroll
    Route::get('/payroll', [\App\Http\Controllers\Api\PayrollController::class, 'index']);
    Route::get('/payroll/{id}', [\App\Http\Controllers\Api\PayrollController::class, 'show']);

    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\Api\AnnouncementController::class, 'index']);

    // Super Admin Routes
    Route::middleware(['role:Super Admin'])->prefix('admin')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\Api\SuperAdminController::class, 'getStats']);
        Route::get('/companies', [\App\Http\Controllers\Api\SuperAdminController::class, 'getCompanies']);
        Route::get('/users', [\App\Http\Controllers\Api\SuperAdminController::class, 'getUsers']);
        Route::get('/subscriptions', [\App\Http\Controllers\Api\SuperAdminController::class, 'getSubscriptions']);
        Route::get('/alerts', [\App\Http\Controllers\Api\SuperAdminController::class, 'getAlerts']);
        
        Route::post('/subscriptions/{id}/approve', [\App\Http\Controllers\Api\SuperAdminController::class, 'approveSubscription']);
        Route::post('/subscriptions/{id}/reject', [\App\Http\Controllers\Api\SuperAdminController::class, 'rejectSubscription']);
        Route::patch('/companies/{id}/status', [\App\Http\Controllers\Api\SuperAdminController::class, 'updateCompanyStatus']);
        Route::patch('/users/{id}/status', [\App\Http\Controllers\Api\SuperAdminController::class, 'updateUserStatus']);
        
        Route::post('/companies/reset-password/{username}', [\App\Http\Controllers\Api\SuperAdminController::class, 'resetAdminPassword']);
        Route::post('/companies/magic-link/{username}', [\App\Http\Controllers\Api\SuperAdminController::class, 'generateMagicLink']);
    });

    // Company HR & Admin Routes
    Route::middleware(['role:Super Admin,Company Admin,HR Manager'])->prefix('hr')->group(function () {
        Route::get('/attendance/today', [\App\Http\Controllers\Api\AttendanceController::class, 'todayAttendance']);
        Route::get('/leaves/pending', [\App\Http\Controllers\Api\LeaveController::class, 'pendingLeaves']);
        Route::patch('/leaves/{leave}/status', [\App\Http\Controllers\Api\LeaveController::class, 'updateStatus']);
    });
});
