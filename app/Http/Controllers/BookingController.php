<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Notifications\BookingCreated;
use Illuminate\Support\Facades\Mail;

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
                $court->today_availability = $court->formatted_opening_time . ' - ' . $court->formatted_closing_time;
                $court->is_available_today = $court->status === 'available';

                return $court;
            });

        // dd($courts);

        return view('players.court-bookings.index', compact('courts'));
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

            // Check if court is available for the requested time
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);

            // Validate court operating hours
            $courtOpenTime = Carbon::parse($court->opening_time);
            $courtCloseTime = Carbon::parse($court->closing_time);

            // Check if start time is before opening time
            if ($startTime->lt($courtOpenTime)) {
                return response()->json([
                    'message' => 'Booking time is outside court operating hours',
                    'errors' => 'Court opens at ' . $courtOpenTime->format('g:i A')
                ], 422);
            }

            // Check if end time is after closing time
            if ($endTime->gt($courtCloseTime)) {
                return response()->json([
                    'message' => 'Booking time is outside court operating hours',
                    'errors' => 'Court closes at ' . $courtCloseTime->format('g:i A')
                ], 422);
            }

            // Check for double bookings
            $existingBooking = Booking::where('court_id', $court->id)
                ->where('user_id', Auth::user()->id)
                ->where('booking_date', $bookingDate)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existingBooking) {
                return response()->json([
                    'message' => 'Time slot is already booked',
                    'errors' => 'This time slot is already booked. Please choose a different date and time.'
                ], 422);
            }

            // Calculate duration in hours
            $duration = $startTime->diffInHours($endTime);

            // Validate minimum booking duration
            if ($duration < 1) {
                return response()->json([
                    'message' => 'Invalid booking duration',
                    'errors' => 'Minimum booking duration is 1 hour'
                ], 422);
            }

            // Calculate total amount based on duration and rates
            $totalAmount = $isWeekend ? $court->weekend_rate_per_hour : $court->rate_per_hour * $duration;

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

            // Send booking confirmation email
            try {
                $user = Auth::user();
                Log::info('Attempting to send booking confirmation email to: ' . $user->email);
                $user->notify(new BookingCreated($booking));
                Log::info('Booking confirmation email sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
                // Continue with the response even if email fails
            }

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
                'message' => 'Court not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while creating your booking'
            ], 500);
        }
    }
}
