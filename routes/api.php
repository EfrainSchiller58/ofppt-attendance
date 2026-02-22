<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\JustificationController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Email diagnostics (temporary)
Route::get('/test-email', function () {
    $config = [
        'mailer'   => config('mail.default'),
        'from'     => config('mail.from'),
        'resend_key_set' => !empty(config('services.resend.key')),
        'resend_key_prefix' => substr(config('services.resend.key', ''), 0, 8) . '...',
    ];

    try {
        \Illuminate\Support\Facades\Mail::raw('Test email from Railway - ' . now()->toIso8601String(), function ($message) {
            $message->to('elmehdisekrare@gmail.com')
                    ->subject('OFPPT Railway Email Test - ' . now()->format('H:i:s'));
        });
        return response()->json(['status' => 'sent', 'config' => $config]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'FAILED',
            'error'  => $e->getMessage(),
            'trace'  => array_slice(explode("\n", $e->getTraceAsString()), 0, 5),
            'config' => $config,
        ], 500);
    }
});

// Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::match(['patch', 'post'], '/me/profile', [AuthController::class, 'updateProfile']);
    Route::patch('/me/change-password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
    Route::get('/dashboard/teacher', [DashboardController::class, 'teacher']);
    Route::get('/dashboard/student', [DashboardController::class, 'student']);
    Route::get('/dashboard/chart', [DashboardController::class, 'chart']);
    Route::get('/dashboard/heatmap', [DashboardController::class, 'heatmap']);

    // CRUD resources
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('groups', GroupController::class);
    Route::get('/groups/{group}/students', [GroupController::class, 'students']);

    // Absences
    Route::get('/absences', [AbsenceController::class, 'index']);
    Route::post('/absences', [AbsenceController::class, 'store']);

    // Justifications
    Route::get('/justifications', [JustificationController::class, 'index']);
    Route::post('/justifications', [JustificationController::class, 'store']);
    Route::patch('/justifications/{justification}/approve', [JustificationController::class, 'approve']);
    Route::patch('/justifications/{justification}/reject', [JustificationController::class, 'reject']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    Route::delete('/notifications/clear', [NotificationController::class, 'clear']);
});
