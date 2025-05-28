<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    /**
     * Display the player dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get 3 featured courts (professional type)
        $featuredCourts = Court::where('type', Court::TYPE_PROFESSIONAL)
            ->where('status', 'available')
            ->orderBy('rate_per_hour', 'desc')
            ->take(3)
            ->get();

        $user = Auth::user();

        // Active (confirmed) bookings
        $activeBookings = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('booking_date', '>=', Carbon::today())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        // Total bookings made
        $totalBookings = Booking::where('user_id', $user->id)->count();

        // Calculate total hours played
        $totalHoursPlayed = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('duration');

        return view('players.index', compact(
            'featuredCourts',
            'activeBookings',
            'totalBookings',
            'totalHoursPlayed'
        ));
    }
}
