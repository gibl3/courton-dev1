<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Notifications\PlayerAccountCreated;
use App\Services\EmailVerificationService;

class AdminUsersController extends Controller
{
    protected $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'player')->withCount('bookings');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Verification filter
        if ($request->has('verified') && $request->verified !== '') {
            if ($request->verified == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Get users with pagination
        $users = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Get statistics
        $stats = [
            'total_users' => User::where('role', 'player')->count(),
            'verified_count' => User::where('role', 'player')
                ->whereNotNull('email_verified_at')
                ->count(),
            'unverified_count' => User::where('role', 'player')
                ->whereNull('email_verified_at')
                ->count(),
            'new_users_today' => User::where('role', 'player')
                ->whereDate('created_at', Carbon::today())
                ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:20'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            // Verify email before creating user
            $emailVerification = $this->emailVerificationService->verifyEmail($validated['email']);
            if (!$emailVerification['is_valid']) {
                return back()
                    ->withInput()
                    ->with('error', 'Email verification failed: ' . $emailVerification['message']);
            }

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'player',
                'email_verified_at' => now(), // Auto-verify the email
            ]);

            // Send welcome email with credentials
            try {
                $user->notify(new PlayerAccountCreated($user, $validated['password']));
                Log::info('Welcome email sent to new player: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send welcome email to new player: ' . $e->getMessage());
                // Continue with the response even if email fails
            }

            return back()
                ->with('success', 'Player created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            Log::error('Error creating player:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'An error occurred while creating the player. Please try again.');
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'password' => ['nullable', 'confirmed', Password::defaults()],
            ]);

            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];

            // Verify email before creating user
            $emailVerification = $this->emailVerificationService->verifyEmail($validated['email']);
            if (!$emailVerification['is_valid']) {
                return back()
                    ->withInput()
                    ->with('error', 'Email verification failed: ' . $emailVerification['message']);
            }

            // Only update password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            return back()
                ->with('success', 'Player updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            Log::error('Error updating player:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'An error occurred while updating the player. Please try again.');
        }
    }

    public function destroy(User $user)
    {
        try {
            // Check if user has any bookings
            if ($user->bookings()->exists()) {
                return back()->with('error', 'Cannot delete player with existing bookings.');
            }

            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Player deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting player:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while deleting the player. Please try again.');
        }
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        Log::info('Search query:', ['q' => $q]);

        $users = User::where('role', 'player')
            ->where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%$q%")
                    ->orWhere('last_name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email']);

        Log::info('Search results:', ['count' => $users->count()]);

        return response()->json($users);
    }
}
