<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Rules\ReCaptchaRule;
use App\Rules\AdminReCaptchaRule;

class AuthController extends Controller
{
    /**
     * The RANS API root URI
     */
    private $ransApiRoot = 'https://e54f-122-54-183-231.ngrok-free.app/api/';

    public function login()
    {
        return view('auth.login');
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

    public function create()
    {
        return view('auth.create');
    }

    public function forgotPass()
    {
        return view('auth.forgotPass');
    }

    public function adminLogin()
    {
        return view('auth.admin-login');
    }

    public function logout(Request $request)
    {
        try {
            // Get the auth token from session
            $authToken = session('rans_auth_token');

            if ($authToken) {
                // Get the API key from config
                $apiKey = config('services.ransAuthApi.api_key');

                // Call the RANS API logout endpoint
                $response = Http::withHeaders([
                    'X-API-Key' => $apiKey,
                    'Authorization' => 'Bearer ' . $authToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->post($this->ransApiRoot . 'logout.php');

                // Log the response for debugging
                Log::info('Logout API Response:', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
            }

            // Clear local session and auth
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welcome');
        } catch (\Exception $e) {
            // Log the error but still proceed with local logout
            Log::error('Logout API Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Still clear local session and auth even if API call fails
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welcome');
        }
    }
}
