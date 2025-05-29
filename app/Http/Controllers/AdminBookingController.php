<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'court']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('court', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->has('date_from')) {
            $query->where('booking_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('booking_date', '<=', $request->date_to);
        }

        // Get bookings with pagination
        $bookings = $query->latest('booking_date')
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
}
