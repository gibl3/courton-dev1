@extends('layouts.player')

@section('title', 'Booking Details - Courton')

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
                    <h1 class="text-2xl font-bold">Booking Details</h1>
                    <p class="text-neutral-600">View your court booking information</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details Card -->
    <section class="bg-white rounded-xl border border-neutral-200 p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Court Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Court Header -->
                <div class="flex items-center gap-4">
                    <div class="size-16 rounded-xl bg-rose-100 flex items-center justify-center">
                        <span class="material-symbols-rounded text-3xl text-rose-600">
                            sports_tennis
                        </span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ $booking->court->name }}</h2>
                        <p class="text-neutral-600">{{ ucfirst($booking->court->type) }} Court</p>
                    </div>
                </div>

                <!-- Court Image -->
                <div class="relative h-64 rounded-xl overflow-hidden">
                    <x-cloudinary::image
                        public-id="{{ $booking->court->image_path }}"
                        alt="{{ $booking->court->name }}"
                        class="size-full aspect-square object-cover rounded-lg border border-neutral-200"
                        fetch-format="auto"
                        quality="auto" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>

                <!-- Court Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-neutral-50">
                        <div class="flex items-center gap-2 text-neutral-600 mb-2">
                            <span class="material-symbols-rounded">schedule</span>
                            <span class="font-medium">Operating Hours</span>
                        </div>
                        <p class="text-sm">{{ $booking->court->formatted_opening_time }} - {{ $booking->court->formatted_closing_time }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-neutral-50">
                        <div class="flex items-center gap-2 text-neutral-600 mb-2">
                            <span class="material-symbols-rounded">group</span>
                            <span class="font-medium">Capacity</span>
                        </div>
                        <p class="text-sm">Max 4 Players</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="space-y-6">
                <!-- Status Badge -->
                <div class="flex justify-end">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                           ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                            'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <!-- Booking Details -->
                <div class="space-y-4">
                    <!-- Date and Time -->
                    <div class="p-4 rounded-lg bg-neutral-50">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2 text-neutral-600">
                                <span class="material-symbols-rounded">calendar_today</span>
                                <p class="font-medium">Booking Schedule</p>
                            </div>
                            <span class="text-sm text-neutral-500">ID: {{ $booking->id }}</span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm">{{ $booking->booking_date->format('l, F d, Y') }}</p>
                            <p class="text-sm text-neutral-600">
                                {{ Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                {{ Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </p>
                            <p class="text-sm text-neutral-600">Duration: {{ $booking->duration }} hours</p>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="p-4 rounded-lg bg-neutral-50">
                        <div class="flex items-center gap-2 text-neutral-600 mb-2">
                            <span class="material-symbols-rounded">payments</span>
                            <span class="font-medium">Payment Details</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-neutral-600">Total Amount</span>
                                <span class="text-lg font-bold text-rose-600">₱{{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-neutral-600">Payment Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($booking->payment_status === 'refunded' ? 'bg-blue-100 text-blue-800' : 
                                        ($booking->payment_status === 'pending_refund' ? 'bg-yellow-100 text-yellow-800' :
                                        ($booking->payment_status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                        'bg-gray-100 text-gray-800'))) }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($booking->cancellation_reason)
                    <!-- Cancellation Reason -->
                    <div class="p-4 rounded-lg bg-red-50">
                        <div class="flex items-center gap-2 text-red-600 mb-2">
                            <span class="material-symbols-rounded">info</span>
                            <span class="font-medium">Cancellation Reason</span>
                        </div>
                        <p class="text-sm text-red-700">{{ $booking->cancellation_reason }}</p>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-y-4">
                    <a href="{{ route('player.bookings.my') }}" class="btn-filled-tonal w-full">
                        <span class="material-symbols-rounded">arrow_back</span>
                        Back to Bookings
                    </a>
                    @if($booking->canBeCancelled())
                    <form action="{{ route('player.bookings.cancel', $booking) }}" method="POST" id="cancel-booking-form" class="flex-1">
                        @csrf
                        <button type="button" class="btn-text text-red-600 w-full" id="cancel-booking">
                            <span class="material-symbols-rounded">cancel</span>
                            Cancel Booking
                        </button>
                    </form>
                    @elseif($booking->canBeDeleted())
                    <form action="{{ route('player.bookings.destroy', $booking) }}" method="POST" class="delete-booking-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-text text-red-600 delete-button">
                            <span class="material-symbols-rounded">delete</span>
                            Delete Booking
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButton = document.getElementById('cancel-booking');
        const cancelForm = document.getElementById('cancel-booking-form');

        if (cancelButton && cancelForm) {
            cancelButton.addEventListener('click', async function() {
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

                // Add the reason to the form
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'reason';
                reasonInput.value = result.value;
                cancelForm.appendChild(reasonInput);

                // Submit the form
                try {
                    const response = await fetch(cancelForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                        text: 'Your booking has been cancelled successfully.',
                        confirmButtonText: 'OK'
                    });

                    // Redirect back to bookings list
                    window.location.href = '{{ route("player.bookings.my") }}';
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const form = this.closest('form');

                const result = await Swal.fire({
                    title: 'Delete Booking',
                    text: 'Are you sure you want to delete this booking? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete Booking',
                    confirmButtonColor: '#dc2626',
                    cancelButtonText: 'No, Keep Booking'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(form.action, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to delete booking');
                        }

                        // Show success message
                        await Swal.fire({
                            icon: 'success',
                            title: 'Booking Deleted',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });

                        // Redirect to bookings list
                        window.location.href = '{{ route("player.bookings.my") }}';
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message,
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    });
</script>
@endpush