<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetOtpMail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Send OTP to user email for password reset
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in password_resets table
        PasswordReset::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // Send OTP via email
        Mail::to($request->email)->send(new PasswordResetOtpMail($otp));

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset OTP has been sent to your email',
        ]);
    }

    /**
     * Verify OTP code sent to email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|min:6|max:6',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP',
            ], 400);
        }

        // Check if OTP is expired (30 minutes)
        $createdAt = Carbon::parse($passwordReset->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 30) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }

        // Generate reset token for next step
        $reset_token = Str::random(60);

        // Instead of using update() on the model instance, use where() and update()
        PasswordReset::where('email', $request->email)
            ->update(['token' => $reset_token]);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully',
            'reset_token' => $reset_token,
        ]);
    }

    /**
     * Reset user password after OTP verification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->reset_token)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid reset token',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset record
        $passwordReset->delete();

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been reset successfully',
        ]);
    }
}
