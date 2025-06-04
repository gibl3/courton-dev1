<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Notifications\OTPVerification;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /**
     * The RANS API root URI
     */
    private $ransApiRoot = 'https://e54f-122-54-183-231.ngrok-free.app/api/';

    /**
     * Show the forgot password form
     */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset OTP
     */
    public function sendResetLink(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email', 'exists:users,email']
            ]);

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to send reset link
            $response = Http::withHeaders([
                'X-Api-Key' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($this->ransApiRoot . 'request-otp.php', [
                'email' => $validated['email'],
                'purpose' => 'password-reset'
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to send verification code',
                    'error' => $responseData['message'] ?? 'Please try again later'
                ], 400);
            }

            // Log the OTP response for debugging
            Log::info('OTP Response:', [
                'response' => $responseData,
                'auth_token' => $responseData['data']['auth_token'] ?? null
            ]);

            // Store email and auth token in session for verification
            session([
                'reset_password_email' => $validated['email'],
                'reset_password_auth_token' => $responseData['data']['auth_token'] ?? null,
                'reset_password_token_expires_at' => $responseData['data']['token_expires_at'] ?? null
            ]);

            // Send OTP to user
            if (isset($responseData['data']['otp'])) {
                $user = User::where('email', $validated['email'])->first();
                $user->notify(new OTPVerification($responseData['data']['otp']));
            }

            return response()->json([
                'message' => 'Verification code has been sent to your email',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while sending verification code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password using OTP
     */
    public function reset(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
                'otp' => ['required', 'string', 'size:6'],
                'password' => ['required', 'confirmed', 'min:8'],
                'password_confirmation' => ['required']
            ]);

            // Get the auth token from session
            $authToken = session('reset_password_auth_token');
            $tokenExpiresAt = session('reset_password_token_expires_at');

            // Log session data for debugging
            Log::info('Reset Password Session Data:', [
                'auth_token' => $authToken,
                'token_expires_at' => $tokenExpiresAt,
                'email' => session('reset_password_email')
            ]);

            if (!$authToken) {
                return response()->json([
                    'message' => 'Session expired. Please request a new verification code.',
                    'error' => 'Auth token not found'
                ], 400);
            }

            // Check if token has expired
            if ($tokenExpiresAt && now()->isAfter($tokenExpiresAt)) {
                return response()->json([
                    'message' => 'Session expired. Please request a new verification code.',
                    'error' => 'Auth token has expired'
                ], 400);
            }

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Log request data for debugging
            Log::info('Reset Password Request:', [
                'api_key' => $apiKey,
                'auth_token' => $authToken,
                'request_body' => [
                    'otp' => $validated['otp'],
                    'new_password' => $validated['password']
                ]
            ]);

            // Make the API request to reset password
            $response = Http::withHeaders([
                'X-Api-Key' => $apiKey,
                'Authorization' => $authToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($this->ransApiRoot . 'reset-password.php', [
                'otp' => $validated['otp'],
                'new_password' => $validated['password']
            ]);

            $responseData = $response->json();

            // Log response for debugging
            Log::info('Reset Password Response:', [
                'status' => $response->status(),
                'response' => $responseData
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to reset password',
                    'error' => $responseData['message'] ?? 'Please try again'
                ], 400);
            }

            // Update password in local database
            $user = User::where('email', $validated['email'])->first();
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            // Clear session
            session()->forget([
                'reset_password_email',
                'reset_password_auth_token',
                'reset_password_token_expires_at'
            ]);

            return response()->json([
                'message' => 'Password has been reset successfully',
                'redirect' => route('auth.login')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while resetting password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
