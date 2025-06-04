<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingCreated;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'court']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('booking_date', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('court', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('booking_date', '<=', $request->date_to);
        }

        // Get bookings with pagination
        $bookings = $query->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        // Get statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::where('status', Booking::STATUS_COMPLETED)
                ->where('payment_status', Booking::PAYMENT_STATUS_PAID)
                ->sum('total_amount'),
            'pending_bookings' => Booking::where('status', Booking::STATUS_PENDING)->count(),
            'today_bookings' => Booking::whereDate('booking_date', Carbon::today())->count(),
            'upcoming_bookings' => Booking::where('booking_date', '>', Carbon::now())
                ->where('status', Booking::STATUS_CONFIRMED)
                ->count(),
        ];

        // Get booking status counts
        $statusCounts = [
            'confirmed' => Booking::where('status', Booking::STATUS_CONFIRMED)->count(),
            'completed' => Booking::where('status', Booking::STATUS_COMPLETED)->count(),
            'cancelled' => Booking::where('status', Booking::STATUS_CANCELLED)->count(),
            'pending' => Booking::where('status', Booking::STATUS_PENDING)->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats', 'statusCounts'));
    }

    public function edit(Booking $booking)
    {
        $users = \App\Models\User::where('role', 'player')->get();
        $courts = \App\Models\Court::where('status', 'available')->get();

        return view('admin.bookings.edit', compact('booking', 'users', 'courts'));
    }

    public function updateStatus(Booking $booking, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_COMPLETED
            ])
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Booking status updated successfully',
            'status' => $booking->status
        ]);
    }

    public function updatePaymentStatus(Booking $booking, Request $request)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:' . implode(',', [
                Booking::PAYMENT_STATUS_PENDING,
                Booking::PAYMENT_STATUS_PAID,
                Booking::PAYMENT_STATUS_REFUNDED
            ])
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Payment status updated successfully',
            'payment_status' => $booking->payment_status
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'bookings' => 'required|array',
            'bookings.*' => 'exists:bookings,id',
            'status' => 'required|in:' . implode(',', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_COMPLETED
            ])
        ]);

        try {
            $bookings = Booking::whereIn('id', $validated['bookings']);

            // Get the count of affected bookings
            $count = $bookings->count();

            // Update all selected bookings
            $bookings->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$count} booking(s) to " . ucfirst($validated['status']),
                'status' => $validated['status']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bookings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdatePayment(Request $request)
    {
        $validated = $request->validate([
            'bookings' => 'required|array',
            'bookings.*' => 'exists:bookings,id',
            'payment_status' => 'required|in:' . implode(',', [
                Booking::PAYMENT_STATUS_PENDING,
                Booking::PAYMENT_STATUS_PAID,
                Booking::PAYMENT_STATUS_REFUNDED
            ])
        ]);

        try {
            $bookings = Booking::whereIn('id', $validated['bookings']);

            // Get the count of affected bookings
            $count = $bookings->count();

            // Update all selected bookings
            $bookings->update(['payment_status' => $validated['payment_status']]);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated payment status for {$count} booking(s) to " . ucfirst($validated['payment_status']),
                'payment_status' => $validated['payment_status']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pending(Request $request)
    {
        $query = Booking::with(['user', 'court']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('court', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Court Type Filter
        if ($request->filled('court_type')) {
            $query->whereHas('court', function ($q) use ($request) {
                $q->where('type', $request->court_type);
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.pending-bookings', compact('bookings'));
    }

    public function pendingPayments(Request $request)
    {
        $query = Booking::with(['user', 'court']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('court', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Court Type Filter
        if ($request->filled('court_type')) {
            $query->whereHas('court', function ($q) use ($request) {
                $q->where('type', $request->court_type);
            });
        }

        // Payment Status Filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.pending-payments', compact('bookings'));
    }

    public function create()
    {
        $users = User::where('role', 'player')->get();
        $courts = Court::where('status', 'available')->get();
        return view('admin.bookings.create', compact('users', 'courts'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'court_id' => 'required|exists:courts,id',
                'booking_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'notes' => 'nullable|string|max:1000',
            ]);

            $court = Court::findOrFail($validated['court_id']);
            $bookingDate = Carbon::parse($validated['booking_date']);
            $isWeekend = $bookingDate->isWeekend();

            // Check if court is available for the requested time
            $startTime = Carbon::parse($validated['start_time']);
            $endTime = Carbon::parse($validated['end_time']);

            // Validate court operating hours
            $courtOpenTime = Carbon::parse($court->opening_time);
            $courtCloseTime = Carbon::parse($court->closing_time);

            // Check if start time is before opening time
            if ($startTime->lt($courtOpenTime)) {
                return redirect()->back()->withErrors([
                    'start_time' => 'Booking time is outside court operating hours. Court opens at ' . $courtOpenTime->format('g:i A')
                ])->withInput();
            }

            // Check if end time is after closing time
            if ($endTime->gt($courtCloseTime)) {
                return redirect()->back()->withErrors([
                    'end_time' => 'Booking time is outside court operating hours. Court closes at ' . $courtCloseTime->format('g:i A')
                ])->withInput();
            }

            // Check for double bookings
            $existingBooking = Booking::where('court_id', $court->id)
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
                return redirect()->back()->withErrors([
                    'time' => 'This time slot is already booked. Please choose a different date and time.'
                ])->withInput();
            }

            // Calculate duration in hours
            $duration = $startTime->diffInHours($endTime);

            // Validate minimum booking duration
            if ($duration < 1) {
                return redirect()->back()->withErrors([
                    'time' => 'Minimum booking duration is 1 hour'
                ])->withInput();
            }

            // Calculate total amount based on duration and rates
            $totalAmount = $isWeekend ? $court->weekend_rate_per_hour : $court->rate_per_hour * $duration;

            // Create the booking
            $booking = Booking::create([
                'user_id' => $validated['user_id'],
                'court_id' => $court->id,
                'booking_date' => $bookingDate,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'duration' => $duration,
                'total_amount' => $totalAmount,
                'status' => Booking::STATUS_PENDING,
                'payment_status' => Booking::PAYMENT_STATUS_PENDING,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Send booking confirmation email
            try {
                $user = User::find($validated['user_id']);
                Log::info('Attempting to send booking confirmation email to: ' . $user->email);
                $user->notify(new BookingCreated($booking));
                Log::info('Booking confirmation email sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
                // Continue with the response even if email fails
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Booking validation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Court not found: ' . $e->getMessage());
            return redirect()->back()->withErrors(['court' => 'Court not found'])->withInput();
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the booking'])->withInput();
        }
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
        ]);

        $court = Court::findOrFail($validated['court_id']);
        $bookingDate = Carbon::parse($validated['booking_date']);
        $isWeekend = $bookingDate->isWeekend();
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        $duration = $startTime->diffInHours($endTime);
        $totalAmount = $isWeekend ? $court->weekend_rate_per_hour : $court->rate_per_hour * $duration;

        $booking->update([
            'user_id' => $validated['user_id'],
            'court_id' => $validated['court_id'],
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $duration,
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
