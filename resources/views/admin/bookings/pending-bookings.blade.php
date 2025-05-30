@extends('layouts.admin')

@section('title', 'Bookings Management - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Pending Bookings</h1>
            <p class="text-sm text-neutral-600">Manage and update all pending court bookings</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Court</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Booking Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $booking->user->getFullNameAttribute() ?? 'N/A' }}</div>
                            <div class="text-sm text-neutral-600">{{ $booking->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $booking->court->name ?? 'N/A' }}</div>
                            <div class="text-sm text-neutral-600">{{ ucfirst($booking->court->type ?? '') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium">{{ $booking->booking_date->format('M d, Y') }}</div>
                                <div class="text-neutral-600">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
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
                            <form id="status-update-{{ $booking->id }}" action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="px-2 py-1 rounded border border-neutral-200">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button type="submit" form="status-update-{{ $booking->id }}" class="btn-outline text-xs">Update</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-neutral-600">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl">calendar_month</span>
                                <p>No pending bookings found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection