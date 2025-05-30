@extends('layouts.admin')

@section('title', 'Pending Payments - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Pending Payments</h1>
            <p class="text-sm text-neutral-600">Manage and update all bookings with pending payments</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Payment</th>
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
                            <form action="{{ route('admin.bookings.updatePaymentStatus', $booking) }}" method="POST" id="payment-update-{{ $booking->id }}">
                                @csrf
                                @method('PUT')
                                <select name="payment_status" class="px-2 py-1 rounded border border-neutral-200">
                                    <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="submit" form="payment-update-{{ $booking->id }}" class="btn-outline text-xs">Update</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-neutral-600">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl">payments</span>
                                <p>No pending payments found</p>
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