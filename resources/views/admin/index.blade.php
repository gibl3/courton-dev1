@extends('layouts.admin')

@section('title', 'Dashboard - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-sm text-neutral-600">Welcome back, {{ Auth::user()->first_name ?? 'Admin' }}!</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.create') }}" class="btn-filled">
                <span class="material-symbols-rounded">add</span>
                New Booking
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Bookings -->
        <div class="bg-white rounded-xl p-6 border border-neutral-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-neutral-600">Total Bookings</h3>
                <span class="material-symbols-rounded text-rose-500">calendar_month</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">{{ number_format($stats['total_bookings']) }}</span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">vs last month</p>
        </div>

        <!-- Active Courts -->
        <div class="bg-white rounded-xl p-6 border border-neutral-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-neutral-600">Active Courts</h3>
                <span class="material-symbols-rounded text-rose-500">sports_tennis</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">{{ number_format($stats['active_courts']) }}</span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">available for booking</p>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl p-6 border border-neutral-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-neutral-600">Total Users</h3>
                <span class="material-symbols-rounded text-rose-500">group</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">{{ number_format($stats['total_users']) }}</span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">vs last month</p>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-xl p-6 border border-neutral-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-neutral-600">Revenue</h3>
                <span class="material-symbols-rounded text-rose-500">payments</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">₱{{ number_format($stats['revenue'], 2) }}</span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">vs last month</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-neutral-200">
            <div class="py-4 px-6 border-b border-neutral-200">
                <h2 class="text-lg font-semibold">Recent Bookings</h2>
            </div>

            <div class="p-6 space-y-6 flex flex-col">
                <div class="space-y-6">
                    @forelse($recentBookings as $booking)
                    <!-- Booking Item -->
                    <div class="flex items-center justify-between rounded-lg bg-neutral-50 p-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-500">sports_tennis</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-medium">{{ $booking->court->name }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 
                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                    'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-600">
                                    {{ $booking->user->first_name }} {{ $booking->user->last_name }} • {{ $booking->duration }} hours
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-rose-700">₱{{ number_format($booking->total_amount, 2) }}</p>
                            <p class="text-sm text-neutral-600">
                                {{ $booking->booking_date->format('M d, Y') }} • {{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-600">
                        <span class="material-symbols-rounded text-4xl mb-2">calendar_month</span>
                        <p>No recent bookings found</p>
                    </div>
                    @endforelse
                </div>
                <div class="ml-auto">
                    <a href="{{ route('admin.bookings.index') }}" class="btn-text">
                        View all bookings
                        <span class="material-symbols-rounded ">arrow_right_alt</span> </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Status -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-neutral-200">
                <div class="p-6 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.courts.create') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-neutral-50 hover:bg-neutral-100 transition-colors">
                            <span class="material-symbols-rounded text-rose-500 mb-2">add</span>
                            <span class="text-sm font-medium">Add Court</span>
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-neutral-50 hover:bg-neutral-100 transition-colors">
                            <span class="material-symbols-rounded text-rose-500 mb-2">person_add</span>
                            <span class="text-sm font-medium">Add Player</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-neutral-50 hover:bg-neutral-100 transition-colors">
                            <span class="material-symbols-rounded text-rose-500 mb-2">receipt_long</span>
                            <span class="text-sm font-medium">Create Bookings</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-neutral-50 hover:bg-neutral-100 transition-colors">
                            <span class="material-symbols-rounded text-rose-500 mb-2">settings</span>
                            <span class="text-sm font-medium">Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection