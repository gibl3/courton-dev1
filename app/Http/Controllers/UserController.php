<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
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

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'player'
            ]);

            // Create player profile
            // $user->player()->create([
            //     'first_name' => $validated['first_name'],
            //     'last_name' => $validated['last_name'],
            //     'phone' => $validated['phone']
            // ]);

            return response()->json([
                'message' => 'Account created successfully',
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
}
