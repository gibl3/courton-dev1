<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class BookingController extends Controller
{
    public function index()
    {
        $courts = Court::where('status', 'available')
            ->orderBy('type')
            ->orderBy('rate_per_hour')
            ->get()
            ->map(function ($court) {
                // Format the availability display
                $court->today_availability = $court->opening_time->format('g:i A') . ' - ' . $court->closing_time->format('g:i A');
                $court->is_available_today = $court->status === 'available';

                return $court;
            });

        return view('players.book', compact('courts'));
    }

    public function confirm(Request $request)
    {
        try {
            $request->validate([
                'court_id' => 'required|exists:courts,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            $court = Court::findOrFail($request->court_id);
            $bookingDate = Carbon::parse($request->date);
            $isWeekend = $bookingDate->isWeekend();

            // Calculate duration in hours
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            $duration = $startTime->diffInHours($endTime);

            // Use appropriate rate based on weekend status
            $rate = $isWeekend ? $court->weekend_rate_per_hour : $court->rate_per_hour;
            $totalAmount = $rate * $duration;

            // Create the booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'court_id' => $court->id,
                'booking_date' => $bookingDate,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration' => $duration,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Booking validation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Court not found: ' . $e->getMessage());
            return response()->json([
                'message' => 'Selected court not found'
            ], 404);
        } catch (QueryException $e) {
            Log::error('Database error during booking creation: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create booking due to database error'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error during booking creation: ' . $e->getMessage());
            return response()->json([
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }
}
