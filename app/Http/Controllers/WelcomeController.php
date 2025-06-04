<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredCourts = Court::withCount('bookings')
            ->where('status', 'available')
            ->orderBy('bookings_count', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('featuredCourts'));
    }
}
