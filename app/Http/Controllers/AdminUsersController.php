<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminUsersController extends Controller
{
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
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'player',
            'password' => bcrypt($validated['password']),
            'email_verified_at' => now(), // Auto-verify admin-created accounts
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Player created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Player updated successfully.');
    }

    public function destroy(User $user)
    {
        // Check if user has any bookings
        if ($user->bookings()->exists()) {
            return back()->with('error', 'Cannot delete player with existing bookings.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Player deleted successfully.');
    }
}
