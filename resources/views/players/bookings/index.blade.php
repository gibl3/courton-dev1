@extends('layouts.player')

@section('title', 'My Bookings - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12">
    <!-- Page Header -->
    <section class="bg-white rounded-xl border border-neutral-200 p-8">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-3xl text-rose-600">
                        calendar_month
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">My Bookings</h1>
                    <p class="text-neutral-600">View and manage your court bookings</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bookings List -->
    <section class="bg-white rounded-xl border border-neutral-200 p-8">
        @if ($bookings->count() > 0)

        <!-- Quick Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <button class="btn-filled filter-btn" data-filter="all">All</button>
            <button class="btn-filled-tonal filter-btn" data-filter="upcoming">Upcoming</button>
            <button class="btn-filled-tonal filter-btn" data-filter="pending">Pending</button>
            <button class="btn-filled-tonal filter-btn" data-filter="past">Past</button>
            <button class="btn-filled-tonal filter-btn" data-filter="cancelled">Cancelled</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Court</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Date & Time</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Duration</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Amount</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Booking Status</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Payment Status</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @foreach ($bookings as $booking)
                    <tr class="hover:bg-neutral-50 booking-row"
                        data-status="{{ $booking->status }}"
                        data-date="{{ $booking->booking_date->format('Y-m-d') }}">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                    <x-cloudinary::image public-id="{{ $booking->court->image_path }}"
                                        alt="{{ $booking->court->name }}"
                                        class="size-full aspect-square object-cover rounded-lg border border-neutral-200"
                                        fetch-format="auto" quality="auto" />
                                </div>
                                <div>
                                    <p class="font-medium">{{ $booking->court->name }}</p>
                                    <p class="text-sm text-neutral-600">{{ ucfirst($booking->court->type) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm">
                                <div class="font-medium">{{ $booking->booking_date->format('M d, Y') }}</div>
                                <div class="text-neutral-600">
                                    {{ Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                    {{ Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-neutral-600">
                            <div class="text-sm">{{ $booking->duration }} hours</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium">₱{{ number_format($booking->total_amount, 2) }}
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $booking->status === 'confirmed'
                                    ? 'bg-green-100 text-green-800'
                                    : ($booking->status === 'cancelled'
                                        ? 'bg-red-100 text-red-800'
                                        : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $booking->payment_status === 'paid'
                                    ? 'bg-green-100 text-green-800'
                                    : ($booking->payment_status === 'refunded'
                                        ? 'bg-blue-100 text-blue-800'
                                        : ($booking->payment_status === 'pending_refund'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($booking->payment_status === 'cancelled'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('player.bookings.show', $booking) }}"
                                    class="btn-text py-1.5 px-3 text-sm">View
                                </a>
                                @if ($booking->canBeCancelled())
                                <form action="{{ route('player.bookings.cancel', $booking) }}"
                                    method="POST" class="cancel-booking-form">
                                    @csrf
                                    <button type="button" class="btn-text py-1.5 px-3 text-sm cancel-button">
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="py-8 px-6 text-center text-neutral-600">
                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-rounded text-4xl">calendar_month</span>
                    <p>No bookings found</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </section>
</div>
@endsection

@push('scripts')
@vite(['resources/js/booking/my-bookings.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButtons = document.querySelectorAll('.cancel-button');

        cancelButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const form = this.closest('form');

                // Create and show modal for cancellation reason
                const result = await Swal.fire({
                    title: 'Cancel Booking',
                    text: 'Please provide a reason for cancellation (optional)',
                    input: 'textarea',
                    inputPlaceholder: 'Enter your reason here...',
                    inputAttributes: {
                        'aria-label': 'Enter your reason here'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel Booking',
                    confirmButtonColor: '#dc2626',
                    cancelButtonText: 'No, Keep Booking',
                    showLoaderOnConfirm: true,
                    preConfirm: (reason) => {
                        return reason;
                    }
                });

                if (!result.isConfirmed) {
                    return;
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            reason: result.value
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to cancel booking');
                    }

                    // Show success message
                    await Swal.fire({
                        icon: 'success',
                        title: 'Booking Cancelled',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });

                    // Reload the page to show updated status
                    window.location.reload();
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endpush