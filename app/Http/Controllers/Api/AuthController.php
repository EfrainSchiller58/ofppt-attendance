<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\EmailChangedMail;
use App\Mail\PasswordChangedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** POST /api/login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Ce compte est désactivé.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    /** POST /api/logout */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }

    /** GET /api/me */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /** PATCH /api/me/profile */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $oldEmail = $user->email;

        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->update($data);
        $user->refresh();

        // Send email notification if email was changed
        if (isset($data['email']) && $data['email'] !== $oldEmail) {
            try {
                // Send to the NEW email address
                Mail::to($user->email)
                    ->send(new EmailChangedMail($user, $oldEmail, $user->email, $request->ip()));
                Log::info('Email changed notification sent to new address: ' . $user->email);

                // Also try to send to the OLD email address (security alert)
                Mail::to($oldEmail)
                    ->send(new EmailChangedMail($user, $oldEmail, $user->email, $request->ip()));
                Log::info('Email changed notification sent to old address: ' . $oldEmail);
            } catch (\Throwable $e) {
                Log::warning('Failed to send email changed notification: ' . $e->getMessage());
            }
        }

        return new UserResource($user);
    }

    /** PATCH /api/me/change-password */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $rules = [
            'new_password' => 'required|string|min:8|confirmed',
        ];

        if (!$user->must_change_password) {
            $rules['current_password'] = 'required|current_password';
        }

        $data = $request->validate($rules);

        $user->update([
            'password' => Hash::make($data['new_password']),
            'must_change_password' => false,
        ]);

        // Send password changed confirmation email
        $emailStatus = 'not_attempted';
        $emailError = null;
        try {
            Log::info('Sending password changed email to: ' . $user->email);
            Mail::to($user->email)
                ->send(new PasswordChangedMail($user, $request->ip()));
            $emailStatus = 'sent';
            Log::info('Password changed email sent successfully to: ' . $user->email);
        } catch (\Throwable $e) {
            $emailStatus = 'failed';
            $emailError = $e->getMessage();
            Log::error('Failed to send password changed email to ' . $user->email . ': ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Password updated successfully.',
            'user' => new UserResource($user->fresh()),
            'email_status' => $emailStatus,
            'email_error' => $emailError,
            'email_to' => $user->email,
        ]);
    }
}
