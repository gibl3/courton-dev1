<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Court;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function storeAdmin()
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@courton.com')->exists();

        if (!$adminExists) {
            User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@courton.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'google_id' => null, 
                'avatar' => null, 
            ]);

            return response()->json([
                'message' => 'Admin user created successfully',
                'credentials' => [
                    'email' => 'admin@courton.com',
                    'password' => 'admin123'
                ]
            ]);
        }

        return response()->json([
            'message' => 'Admin user already exists'
        ], 400);
    }

    public function index()
    {
        // Fetch recent bookings with related data
        $recentBookings = Booking::with(['user', 'court'])
            ->latest()
            ->take(5)
            ->get();

        // Get dashboard statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'active_courts' => Court::where('status', 'available')->count(),
            'total_users' => User::count(),
            'revenue' => Booking::where('status', Booking::STATUS_COMPLETED)
                ->where('payment_status', Booking::PAYMENT_STATUS_PAID)
                ->sum('total_amount'),
        ];

        return view('admin.index', compact('recentBookings', 'stats'));
    }
}
