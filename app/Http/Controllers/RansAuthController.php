<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ReCaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Notifications\OTPVerification;
use App\Rules\AdminReCaptchaRule;

class RansAuthController extends Controller
{

    /**
     * Register a new user through RANS API
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:20'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'password_confirmation' => ['required'],
                'terms' => ['required']
            ]);

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to register the user
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post('https://df8f-122-54-183-231.ngrok-free.app/api/register.php', [
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Registration failed',
                    'error' => $responseData['message'] ?? 'Failed to register user'
                ], 400);
            }

            // Create user in local database
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'player'
            ]);

            // Send OTP verification email
            // if (isset($responseData['data']['otp'])) {
            //     $user->notify(new OTPVerification($responseData['data']['otp']));
            // }

            return response()->json([
                'message' => 'Registration successful. Please check your email for the verification code.',
                'user' => $responseData['data'],
                'redirect' => route('auth.showOTP')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating account',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showOTP()
    {
        return view('auth.showOTP');
    }

    /**
     * Verify OTP through RANS API
     */
    public function verifyOTP(Request $request)
    {
        try {
            $validated = $request->validate([
                'otp' => ['required', 'string', 'size:6']
            ]);

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post('https://df8f-122-54-183-231.ngrok-free.app/api/verify-email.php', [
                'email' => Auth::user()->email,
                'otp' => $validated['otp'],
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'OTP verification failed',
                    'error' => $responseData['message'] ?? 'Invalid OTP'
                ], 400);
            }

            // Update email_verified_at
            User::where('id', Auth::id())->update([
                'email_verified_at' => now()
            ]);

            return response()->json([
                'message' => 'Email verified successfully',
                'redirect' => route('player.dashboard')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during OTP verification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resend OTP through RANS API
     */
    public function resendOTP(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'message' => 'Not authenticated',
                    'error' => 'Please login first'
                ], 401);
            }

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to resend OTP
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post('https://df8f-122-54-183-231.ngrok-free.app/api/resend-otp.php', [
                'email' => Auth::user()->email,
                'purpose' => 'email-verification'
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to resend OTP',
                    'error' => $response->json()['message'] ?? 'Please try again later'
                ], 400);
            }

            $responseData = $response->json();
            if (isset($responseData['data']['otp'])) {
                Auth::user()->notify(new OTPVerification($responseData['data']['otp']));
            }

            return response()->json([
                'message' => 'OTP resent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while resending OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function authenticate(Request $request)
    {
        try {
            // Determine if this is an admin login attempt
            $isAdminLogin = $request->is('auth/login/admin') || $request->has('admin_login');

            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => 'required',
                'g-recaptcha-response' => [
                    $isAdminLogin ? new AdminReCaptchaRule : new ReCaptchaRule
                ],
            ]);

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to verify login
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post('https://df8f-122-54-183-231.ngrok-free.app/api/login.php', [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Invalid credentials',
                    'error' => $responseData['message'] ?? 'Authentication failed'
                ], 401);
            }

            // Attempt to authenticate the user locally
            if (Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ])) {
                $request->session()->regenerate();

                // Check if user is admin and redirect accordingly
                if (Auth::user()->role === 'admin') {
                    return response()->json([
                        'message' => 'Logged in successfully',
                        'redirect' => route('admin.index')
                    ]);
                }

                // Check if email is verified
                if (!Auth::user()->email_verified_at) {
                    // Store email in session for OTP verification
                    session(['verification_email' => $credentials['email']]);

                    // Request new OTP
                    $otpResponse = Http::withHeaders([
                        'X-API-Key' => $apiKey,
                        'Accept' => 'application/json',
                    ])->post('https://df8f-122-54-183-231.ngrok-free.app/api/resend-otp.php', [
                        'email' => $credentials['email'],
                        'purpose' => 'email-verification'
                    ]);

                    if ($otpResponse->successful()) {
                        $otpData = $otpResponse->json();
                        if (isset($otpData['data']['otp'])) {
                            Auth::user()->notify(new OTPVerification($otpData['data']['otp']));
                        }
                    }

                    return response()->json([
                        'message' => 'Please verify your email first',
                        'redirect' => route('auth.showOTP')
                    ]);
                }

                return response()->json([
                    'message' => 'Logged in successfully',
                    'redirect' => route('player.dashboard')
                ]);
            }

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while logging in',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
