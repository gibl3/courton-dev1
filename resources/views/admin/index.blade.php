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
                <span class="text-sm text-emerald-600">+12%</span>
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
                <span class="text-sm text-emerald-600">+2</span>
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
                <span class="text-sm text-emerald-600">+8%</span>
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
                <span class="text-2xl font-bold">${{ number_format($stats['revenue'], 2) }}</span>
                <span class="text-sm text-emerald-600">+15%</span>
            </div>
            <p class="text-xs text-neutral-500 mt-1">vs last month</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-neutral-200">
            <div class="p-6 border-b border-neutral-200">
                <h2 class="text-lg font-semibold">Recent Bookings</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentBookings as $booking)
                    <!-- Booking Item -->
                    <div class="flex items-center justify-between p-4 rounded-lg bg-neutral-50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-500">sports_tennis</span>
                            </div>
                            <div>
                                <h4 class="font-medium">{{ $booking->court->name }}</h4>
                                <p class="text-sm text-neutral-600">
                                    {{ $booking->user->first_name }} {{ $booking->user->last_name }} •
                                    {{ $booking->duration }} hours
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">₱{{ number_format($booking->total_amount, 2) }}</p>
                            <p class="text-sm text-neutral-600">
                                {{ $booking->booking_date->format('M d, Y') }},
                                {{ $booking->start_time->format('H:i') }}
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
                <div class="mt-6">
                    <a href="{{ route('admin.bookings') }}" class="text-sm text-rose-600 hover:text-rose-700 font-medium">View all bookings →</a>
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
                            <span class="text-sm font-medium">Add User</span>
                        </a>
                        <!-- <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-neutral-50 hover:bg-neutral-100 transition-colors">
                            <span class="material-symbols-rounded text-rose-500 mb-2">receipt_long</span>
                            <span class="text-sm font-medium">Reports</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-neutral-50 hover:bg-neutral-100 transition-colors">
                            <span class="material-symbols-rounded text-rose-500 mb-2">settings</span>
                            <span class="text-sm font-medium">Settings</span>
                        </a> -->
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-xl border border-neutral-200">
                <div class="p-6 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold">System Status</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                                <span class="text-sm">Server Status</span>
                            </div>
                            <span class="text-sm text-emerald-600">Operational</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                                <span class="text-sm">Database</span>
                            </div>
                            <span class="text-sm text-emerald-600">Connected</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                                <span class="text-sm">Last Backup</span>
                            </div>
                            <span class="text-sm text-neutral-600">2 hours ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection