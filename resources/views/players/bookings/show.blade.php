@extends('layouts.player')

@section('title', 'Booking Details - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12">
    <!-- Page Header -->
    <section class="bg-white rounded-2xl shadow-lg p-8">
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
    <section class="bg-white rounded-2xl shadow-lg p-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Court Information -->
            <div class="space-y-6">
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
                    <img src="{{ $booking->court->image_path }}" alt="{{ $booking->court->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>

                <!-- Court Details -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 text-neutral-600">
                        <span class="material-symbols-rounded">schedule</span>
                        <span>{{ $booking->court->opening_time->format('g:i A') }} - {{ $booking->court->closing_time->format('g:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-neutral-600">
                        <span class="material-symbols-rounded">group</span>
                        <span>Max Capacity: 4 Players</span>
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
                    <div class="p-4 rounded-lg bg-neutral-50">
                        <h3 class="font-medium mb-2">Booking Date</h3>
                        <p class="text-neutral-600">{{ $booking->booking_date->format('l, F d, Y') }}</p>
                    </div>

                    <div class="p-4 rounded-lg bg-neutral-50">
                        <h3 class="font-medium mb-2">Time Slot</h3>
                        <p class="text-neutral-600">
                            {{ Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                            {{ Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                        </p>
                        <p class="text-sm text-neutral-500 mt-1">Duration: {{ $booking->duration }} hours</p>
                    </div>

                    <div class="p-4 rounded-lg bg-neutral-50">
                        <h3 class="font-medium mb-2">Payment Details</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-neutral-600">Total Amount</span>
                            <span class="text-lg font-bold text-rose-600">₱{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-neutral-600">Payment Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($booking->payment_status === 'refunded' ? 'bg-blue-100 text-blue-800' : 
                                    'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6">
                    <a href="{{ route('player.myBookings') }}" class="btn-filled-tonal flex-1">
                        <span class="material-symbols-rounded">arrow_back</span>
                        Back to Bookings
                    </a>
                    @if($booking->canBeCancelled())
                    <button class="btn-filled flex-1 text-red-600 bg-red-50 hover:bg-red-100" id="cancel-booking">
                        <span class="material-symbols-rounded">cancel</span>
                        Cancel Booking
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush