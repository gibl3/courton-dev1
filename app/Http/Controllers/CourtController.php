<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::all();
        $courts = Court::all()->map(function ($court) {
            // Format the availability display
            $court->today_availability = $court->formatted_opening_time . ' - ' . $court->formatted_closing_time;
            $court->is_available_today = $court->status === 'available';

            return $court;
        });

        return view('courts.index', compact('courts'));
    }

    public function create()
    {
        return view('courts.create');
    }

    public function store(Request $request)
    {
        // Hardcoded courts data
        $courts = [
            [
                'name' => 'Court A',
                'type' => 'professional',
                'description' => 'Professional-grade court with premium flooring and lighting system.',
                'image_path' => '/images/court-1.jpg',
                'status' => 'available',
                'rate_per_hour' => 200.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court B',
                'type' => 'standard',
                'description' => 'Standard court with quality flooring and adequate lighting.',
                'image_path' => '/images/court-2.jpg',
                'status' => 'available',
                'rate_per_hour' => 150.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court C',
                'type' => 'training',
                'description' => 'Perfect for training sessions with professional coaches.',
                'image_path' => '/images/court-3.jpg',
                'status' => 'available',
                'rate_per_hour' => 180.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court D',
                'type' => 'professional',
                'description' => 'Olympic-sized court with spectator seating.',
                'image_path' => '/images/court-4.jpg',
                'status' => 'available',
                'rate_per_hour' => 250.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court E',
                'type' => 'standard',
                'description' => 'Mid-range court suitable for casual and competitive play.',
                'image_path' => '/images/court-1.jpg',
                'status' => 'available',
                'rate_per_hour' => 120.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court F',
                'type' => 'training',
                'description' => 'Training court with video recording capabilities.',
                'image_path' => '/images/court-2.jpg',
                'status' => 'available',
                'rate_per_hour' => 190.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court G',
                'type' => 'professional',
                'description' => 'VIP court with luxury amenities and private access.',
                'image_path' => '/images/court-3.jpg',
                'status' => 'available',
                'rate_per_hour' => 300.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
            [
                'name' => 'Court H',
                'type' => 'training',
                'description' => 'Climate-controlled indoor court available year-round.',
                'image_path' => '/images/court-4.jpg',
                'status' => 'available',
                'rate_per_hour' => 220.00,
                'weekend_rate_per_hour' => 100.00,
                'opening_time' => '06:00 AM',
                'closing_time' => '10:00 PM',
            ],
        ];


        // Create each court
        foreach ($courts as $courtData) {
            Court::create($courtData);
        }

        return redirect()->route('courts.index')
            ->with('success', 'Courts created successfully.');
    }

    public function storeCourts() {}
}
