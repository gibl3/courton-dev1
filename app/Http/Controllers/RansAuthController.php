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
     * The RANS API root URI
     */
    private $ransApiRoot = 'https://e54f-122-54-183-231.ngrok-free.app/api/';

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
            ])->post($this->ransApiRoot . '/register.php', [
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
                'redirect' => route('auth.login')
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

            // Get email from session
            $email = $request->session()->get('pending_verification_email');
            if (!$email) {
                return response()->json([
                    'message' => 'Verification session expired',
                    'error' => 'Please log in again'
                ], 401);
            }

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post($this->ransApiRoot . 'verify-email.php', [
                'email' => $email,
                'otp' => $validated['otp'],
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'OTP verification failed',
                    'error' => $responseData['message'] ?? 'Invalid OTP'
                ], 400);
            }

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to verify login
            $loginResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post($this->ransApiRoot . 'login.php', [
                'email' => $request->session()->get('rans_auth_token'),
                'password' => $request->session()->get('rans_auth_token'),
            ]);


            $loginResponseData = $loginResponse->json();

            if ($loginResponse->successful()) {
                // Store RANS auth token in session
                if (isset($loginResponseData['data']['auth_token'])) {
                    $request->session()->put('rans_auth_token', $loginResponseData['data']['auth_token']);
                    $request->session()->put('rans_token_expires_at', $loginResponseData['data']['expires_at']);
                }

                // Get user data from RANS API response
                $userData = $loginResponseData['data'] ?? null;

                if ($userData) {
                    // Create or update user in local database
                    $user = User::firstOrCreate(
                        ['email' => $userData['email']],
                        [
                            'first_name' => '',  // These fields will be empty initially
                            'last_name' => '',   // as they're not provided in the API response
                            'password' => Hash::make($request->session()->get('rans_auth_token')),
                            'role' => 'player'
                        ]
                    );

                    // Clear the pending verification data from session
                    $request->session()->forget(['pending_verification_email', 'pending_password']);
                    // Log the user in
                    Auth::login($user);
                    $request->session()->regenerate();

                    return response()->json([
                        'message' => 'Logged in successfully',
                        'redirect' => route('player.dashboard')
                    ]);
                }
            }


            // return response()->json([
            //     'message' => 'Email verified successfully',
            //     'redirect' => route('player.dashboard')
            // ]);
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
            // Get registration data from session
            $registrationData = $request->session()->get('registration_data');
            if (!$registrationData) {
                return response()->json([
                    'message' => 'Registration session expired',
                    'error' => 'Please register again'
                ], 400);
            }

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to resend OTP
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post($this->ransApiRoot . '/resend-otp.php', [
                'email' => $registrationData['email'],
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to resend OTP',
                    'error' => $response->json()['message'] ?? 'Please try again later'
                ], 400);
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
                // 'g-recaptcha-response' => [
                //     $isAdminLogin ? new AdminReCaptchaRule : new ReCaptchaRule
                // ],
            ]);

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Make the API request to verify login
            $loginResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post($this->ransApiRoot . 'login.php', [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]);

            $loginResponseData = $loginResponse->json();
            // dd($loginResponseData);

            if ($loginResponse->successful()) {
                // Store RANS auth token in session
                if (isset($loginResponseData['data']['auth_token'])) {
                    $request->session()->put('rans_auth_token', $loginResponseData['data']['auth_token']);
                    $request->session()->put('rans_token_expires_at', $loginResponseData['data']['expires_at']);
                }

                // Get user data from RANS API response
                $userData = $loginResponseData['data'] ?? null;

                if ($userData) {
                    // Create or update user in local database
                    $user = User::firstOrCreate(
                        ['email' => $userData['email']],
                        [
                            'first_name' => '',  // These fields will be empty initially
                            'last_name' => '',   // as they're not provided in the API response
                            'password' => Hash::make($credentials['password']),
                            'role' => 'player'
                        ]
                    );

                    // Log the user in
                    Auth::login($user);
                    $request->session()->regenerate();

                    return response()->json([
                        'message' => 'Logged in successfully',
                        'redirect' => route('player.dashboard')
                    ]);
                }
            } else {
                // If login failed, check if we need to request OTP
                $otpResponse = Http::withHeaders([
                    'X-API-Key' => $apiKey,
                    'Accept' => 'application/json',
                ])->post($this->ransApiRoot . 'request-otp.php', [
                    'email' => $credentials['email'],
                    'purpose' => 'email-verification'
                ]);

                $otpData = $otpResponse->json();

                if ($otpResponse->successful() && isset($otpData['data']['otp'])) {
                    // Create or get user for OTP notification
                    $user = User::firstOrCreate(
                        ['email' => $credentials['email']],
                        [
                            'first_name' => '',
                            'last_name' => '',
                            'password' => Hash::make($credentials['password']),
                            'role' => 'player'
                        ]
                    );

                    // Store email and password in session for OTP verification
                    $request->session()->put('pending_verification_email', $credentials['email']);
                    $request->session()->put('pending_password', $credentials['password']);

                    $user->notify(new OTPVerification($otpData['data']['otp']));

                    return response()->json([
                        'message' => 'Please verify your email first',
                        'redirect' => route('auth.showOTP')
                    ]);
                }

                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
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

    /**
     * Change user password both locally and through RANS API
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'string'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'password_confirmation' => ['required'],
            ]);

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Get the stored RANS auth token
            $authToken = $request->session()->get('rans_auth_token');

            // dd($authToken);
            if (!$authToken) {
                return response()->json([
                    'message' => 'Authentication token not found',
                    'error' => 'Please log in again'
                ], 401);
            }

            // Make the API request to change password
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Authorization' => $authToken,
                'Accept' => 'application/json',
            ])->post($this->ransApiRoot . 'change-password.php', [
                'old_password' => $validated['current_password'],
                'new_password' => $validated['password'],
                'confirm_password' => $validated['password_confirmation'],
            ]);

            // dd($response->json());

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Password change failed',
                    'error' => $response->json()['message'] ?? 'Failed to change password'
                ], 400);
            }

            // Update password in local database
            $user = Auth::user();
            $user->password = Hash::make($validated['password']);
            $user->save();

            return response()->json([
                'message' => 'Password changed successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while changing password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete user account both from RANS API and local database
     */
    public function deleteUser(Request $request)
    {
        try {
            $request->validate([
                'password' => ['required', 'string'],
            ]);

            $user = Auth::user();

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Incorrect password'
                ], 422);
            }

            // Get the API key from config
            $apiKey = config('services.ransAuthApi.api_key');

            // Get the stored RANS auth token
            $authToken = $request->session()->get('rans_auth_token');

            if (!$authToken) {
                return response()->json([
                    'message' => 'Authentication token not found',
                    'error' => 'Please log in again'
                ], 401);
            }

            // Make the API request to delete user
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post($this->ransApiRoot . 'delete-user.php', [
                'email' => $user->email,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to delete user account from RANS API',
                    'error' => $response->json()['message'] ?? 'Failed to delete user'
                ], 400);
            }

            // Delete user's bookings first
            $user->bookings()->delete();

            // Delete user from local database
            $user->delete();

            // Clear the session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Account deleted successfully',
                'redirect' => route('auth.login')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
