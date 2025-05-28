<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function view(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can update the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function update(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can delete the booking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function delete(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id;
    }
}
