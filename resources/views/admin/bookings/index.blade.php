@extends('layouts.admin')

@section('title', 'Bookings Management - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Bookings Management</h1>
            <p class="text-sm text-neutral-600">Manage and monitor all court bookings</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn-filled flex items-center gap-2">
                <span class="material-symbols-rounded">add</span>
                New Booking
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Booking Status Overview -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Booking Status</p>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-medium">Confirmed: {{ $statusCounts['confirmed'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-sm font-medium">Completed: {{ $statusCounts['completed'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-sm font-medium">Pending: {{ $statusCounts['pending'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span class="text-sm font-medium">Cancelled: {{ $statusCounts['cancelled'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Overview -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Today's Overview</p>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm text-neutral-400">today</span>
                            <span class="text-sm font-medium">Bookings: {{ $stats['today_bookings'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm text-neutral-400">upcoming</span>
                            <span class="text-sm font-medium">Upcoming: {{ $stats['upcoming_bookings'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Overview -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Revenue Overview</p>
                    <div class="mt-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm text-neutral-400">payments</span>
                            <span class="text-sm font-medium">${{ number_format($stats['total_revenue'], 2) }}</span>
                        </div>
                        <div class="mt-1 text-xs text-neutral-500">
                            Total completed & paid
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Total Bookings</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_bookings']) }}</h3>
                    <div class="mt-1 text-xs text-neutral-500">
                        All time bookings
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user or court..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div class="w-full md:w-48">
                <select name="payment_status" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    <option value="">All Payments</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="flex gap-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full md:w-40 px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full md:w-40 px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Court</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-neutral-600">person</span>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $booking->user->name }}</div>
                                    <div class="text-sm text-neutral-600">{{ $booking->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-neutral-600">sports_tennis</span>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $booking->court->name }}</div>
                                    <div class="text-sm text-neutral-600">{{ ucfirst($booking->court->type) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium">{{ $booking->booking_date->format('M d, Y') }}</div>
                                <div class="text-neutral-600">
                                    {{ Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                    {{ Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium">${{ number_format($booking->total_amount, 2) }}</div>
                                <div class="text-neutral-600">{{ $booking->duration }} hours</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 
                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                    'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($booking->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 
                                    'bg-red-100 text-red-800') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 text-neutral-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <span class="material-symbols-rounded">edit</span>
                                </button>
                                <button class="p-2 text-neutral-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <span class="material-symbols-rounded">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-neutral-600">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl">calendar_month</span>
                                <p>No bookings found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection