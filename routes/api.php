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

    $results = [];

    // Test 1: Raw text email (no Blade view)
    try {
        \Illuminate\Support\Facades\Mail::raw('Raw test from Railway - ' . now()->toIso8601String(), function ($message) {
            $message->to('elmehdisekrare@gmail.com')
                    ->subject('Test 1 Raw - ' . now()->format('H:i:s'));
        });
        $results['raw_email'] = 'OK';
    } catch (\Throwable $e) {
        $results['raw_email'] = 'FAILED: ' . $e->getMessage();
    }

    // Test 2: Mailable with Blade view (PasswordChangedMail)
    try {
        $user = \App\Models\User::where('email', 'elmehdisekrare@gmail.com')->first();
        if (!$user) {
            $user = \App\Models\User::first();
        }
        $results['test_user'] = $user ? $user->email : 'NO USER FOUND';

        if ($user) {
            \Illuminate\Support\Facades\Mail::to('elmehdisekrare@gmail.com')
                ->send(new \App\Mail\PasswordChangedMail($user, '127.0.0.1'));
            $results['mailable_email'] = 'OK';
        }
    } catch (\Throwable $e) {
        $results['mailable_email'] = 'FAILED: ' . $e->getMessage();
        $results['mailable_trace'] = array_slice(explode("\n", $e->getTraceAsString()), 0, 8);
    }

    // Test 3: Check if views exist
    try {
        $viewExists = view()->exists('emails.password-changed');
        $results['view_exists'] = $viewExists ? 'YES' : 'NO';
    } catch (\Throwable $e) {
        $results['view_exists'] = 'ERROR: ' . $e->getMessage();
    }

    // Test 4: Try rendering the view standalone
    try {
        $html = view('emails.password-changed', [
            'userName' => 'Test User',
            'email' => 'test@test.com',
            'changedAt' => now()->format('d/m/Y à H:i'),
            'ipAddress' => '127.0.0.1',
            'appUrl' => 'https://ofppt-futurelearn.vercel.app',
        ])->render();
        $results['view_render'] = 'OK (' . strlen($html) . ' chars)';
    } catch (\Throwable $e) {
        $results['view_render'] = 'FAILED: ' . $e->getMessage();
    }

    return response()->json(['config' => $config, 'results' => $results]);
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
