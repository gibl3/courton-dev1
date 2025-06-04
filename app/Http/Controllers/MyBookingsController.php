<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingCancelled;

class MyBookingsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the player's bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('court')
            ->orderBy('created_at', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('players.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return view('players.bookings.show', compact('booking'));
    }

    /**
     * Cancel the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, Booking $booking)
    {
        try {
            $this->authorize('update', $booking);

            if (!$booking->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be cancelled. Please check the cancellation policy.'
                ], 422);
            }

            // Validate cancellation reason
            $request->validate([
                'reason' => 'nullable|string|max:500'
            ]);

            // Determine payment status based on current status
            $paymentStatus = $booking->payment_status;
            if ($paymentStatus === Booking::PAYMENT_STATUS_PAID) {
                // If already paid, set to pending refund
                $paymentStatus = Booking::PAYMENT_STATUS_PENDING_REFUND;
            } else {
                // If not paid yet, just mark as cancelled
                $paymentStatus = Booking::PAYMENT_STATUS_CANCELLED;
            }

            // Update booking status
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'payment_status' => $paymentStatus,
                'cancellation_reason' => $request->reason
            ]);

            // Send cancellation notification
            try {
                $user = Auth::user();
                Log::info('Sending booking cancellation notification to: ' . $user->email);
                $user->notify(new BookingCancelled($booking, $request->reason));
                Log::info('Booking cancellation notification sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send booking cancellation notification: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully' .
                    ($paymentStatus === Booking::PAYMENT_STATUS_PENDING_REFUND ?
                        '. Your refund is being processed.' : '')
            ]);
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while cancelling your booking'
            ], 500);
        }
    }

    /**
     * Delete the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $booking)
    {
        try {
            $this->authorize('delete', $booking);

            if (!$booking->canBeDeleted()) {
                return response()->json([
                    'message' => 'This booking cannot be deleted. Please check if it meets the deletion criteria.'
                ], 422);
            }

            // Delete the booking
            $booking->delete();

            return response()->json([
                'message' => 'Booking deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Booking deletion failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while deleting your booking'
            ], 500);
        }
    }
}
