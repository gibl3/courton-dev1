<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredCourts = Court::where('type', 'professional')
            ->where('status', 'available')
            ->orderBy('rate_per_hour', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('featuredCourts'));
    }
}
