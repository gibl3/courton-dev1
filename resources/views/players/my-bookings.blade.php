@extends('layouts.player')

@section('title', 'My Bookings - Courton')

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
                    <h1 class="text-2xl font-bold">My Bookings</h1>
                    <p class="text-neutral-600">View and manage your court bookings</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bookings List -->
    <section class="bg-white rounded-2xl shadow-lg p-6">
        <!-- Quick Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <button class="btn-filled filter-btn" data-filter="all">All</button>
            <button class="btn-filled-tonal filter-btn" data-filter="upcoming">Upcoming</button>
            <button class="btn-filled-tonal filter-btn" data-filter="pending">Pending</button>
            <button class="btn-filled-tonal filter-btn" data-filter="past">Past</button>
            <button class="btn-filled-tonal filter-btn" data-filter="cancelled">Cancelled</button>
        </div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Court</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Date</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Time</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Status</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-neutral-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($bookings as $booking)
                    <tr class="booking-row"
                        data-status="{{ $booking->status }}"
                        data-date="{{ $booking->booking_date->format('Y-m-d') }}">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-rose-600">
                                        sports_tennis
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium">{{ $booking->court->name }}</p>
                                    <p class="text-sm text-neutral-600">{{ ucfirst($booking->court->type) }} Court</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-neutral-600">{{ $booking->booking_date->format('M d, Y') }}</td>
                        <td class="py-4 px-6 text-neutral-600">
                            {{ Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                            {{ Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                    ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-neutral-100 text-neutral-800')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex gap-2">
                                <a href="{{ route('player.bookings.show', $booking->id) }}" class="btn-base text-sm">View</a>
                                @if(($booking->status === 'confirmed' || $booking->status === 'pending') && $booking->canBeCancelled())
                                <button class="btn-base text-sm text-red-600 cancel-booking" data-booking-id="{{ $booking->id }}">Cancel</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-neutral-600">
                            No bookings found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
@vite(['resources/js/booking/my-bookings.js'])
@endpush