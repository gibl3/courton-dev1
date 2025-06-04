<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the player's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('players.profile.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:20'],
            ]);

            // Check if data has actually changed
            if (
                $user->first_name === $validated['first_name'] &&
                $user->last_name === $validated['last_name'] &&
                $user->phone === $validated['phone']
            ) {
                return response()->json([
                    'message' => 'No changes were made',
                    'user' => $user
                ], 304); // Not Modified
            }

            try {
                $user->fill($validated);
                $user->save();

                return response()->json([
                    'message' => 'Profile updated successfully',
                    'user' => $user
                ], 200); // OK
            } catch (\Exception $e) {
                return response()->json([
                    'errors' => 'Failed to update profile. Please try again.'
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
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

            // Delete user's bookings first
            $user->bookings()->delete();

            // Delete the user
            $user->delete();

            // Logout the user
            Auth::logout();

            return response()->json([
                'message' => 'Account deleted successfully',
                'redirect' => route('home')
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
