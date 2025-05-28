@extends('layouts.player')

@section('title', 'Dashboard - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12">
    <!-- Hero Section with Book Now -->
    <section class="relative flex justify-center h-64 rounded-4xl bg-[url('/public/images/hero-bg.jpg')] bg-cover bg-center">
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-900/40 to-neutral-900/0 backdrop-blur-[4px] rounded-4xl"></div>

        <!-- Header Actions -->
        <div class="relative z-10 flex flex-col gap-y-8 items-center justify-center">
            <div class="flex flex-col gap-6 text-neutral-50">
                <div class="flex flex-col items-center gap-2">
                    <h1 class="text-4xl font-bold">Ready to play?</h1>
                    <p class="text-center">Book your court now and enjoy a great game!</p>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('player.book') }}" class="btn-filled pl-6 pr-[16px]">
                    Book a Court
                    <span class="material-symbols-rounded md-icon-24">
                        chevron_right
                    </span>
                </a>
                <a href="{{ route('player.book') }}?focus=search" class="btn-filled-tonal pl-6 pr-[16px]">
                    View Available Courts
                    <span class="material-symbols-rounded md-icon-24">
                        search
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-3xl text-rose-600">
                        account_circle
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->getFullNameAttribute()}}!</h1>
                    <p class="text-neutral-600">Here's what's happening with your bookings today.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Active Bookings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-2xl text-rose-600">
                        calendar_month
                    </span>
                </div>
                <div>
                    <p class="text-sm text-neutral-600">Active Bookings</p>
                    <h3 class="text-2xl font-bold">{{ $activeBookings->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-2xl text-rose-600">
                        history
                    </span>
                </div>
                <div>
                    <p class="text-sm text-neutral-600">Total Bookings</p>
                    <h3 class="text-2xl font-bold">{{ $totalBookings }}</h3>
                </div>
            </div>
        </div>

        <!-- Hours Played -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-2xl text-rose-600">
                        timer
                    </span>
                </div>
                <div>
                    <p class="text-sm text-neutral-600">Hours Played</p>
                    <h3 class="text-2xl font-bold">{{ $totalHoursPlayed }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courts -->
    <section class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold">Featured Courts</h2>
                <a href="{{ route('player.book') }}" class="btn-filled-tonal text-sm">View All Courts</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredCourts as $court)
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden group hover:shadow-lg transition-all">
                    <div class="relative h-48">
                        <img src="{{ $court->image_path }}" alt="{{ $court->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="text-lg font-semibold">{{ $court->name }}</h3>
                            <p class="text-sm text-neutral-200">{{ ucfirst($court->type) }} Court</p>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center gap-x-2 text-rose-600">
                            <span class="material-symbols-rounded">schedule</span>
                            <span>{{ $court->opening_time->format('g:i A') }} - {{ $court->closing_time->format('g:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-lg font-bold">₱{{ number_format($court->rate_per_hour, 2) }}</span>
                                <span class="text-sm text-neutral-600">Weekday: per hour</span>
                                <span class="text-sm text-neutral-600">Weekend: whole day</span>
                            </div>
                            <a href="{{ route('player.book') }}" class="btn-filled text-sm">Book Now</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Upcoming Bookings -->
    <section class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold">Upcoming Bookings</h2>
                <a href="{{ route('player.myBookings') }}" class="btn-filled-tonal text-sm">View All Bookings</a>
            </div>

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
                        @forelse($activeBookings as $booking)
                        <tr>
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
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <button class="btn-base text-sm">View Details</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-neutral-600">
                                No upcoming bookings found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection