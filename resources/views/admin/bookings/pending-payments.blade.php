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

    <!-- Filters Section -->
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <form action="{{ route('admin.bookings.pendingPayments') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or court..."
                        class="input-base pl-10">
                </div>
            </div>

            <!-- Court Type Filter -->
            <div class="w-full md:w-48">
                <select name="court_type" class="input-base">
                    <option value="">All Court Types</option>
                    <option value="professional" {{ request('court_type') == 'professional' ? 'selected' : '' }}>Professional</option>
                    <option value="standard" {{ request('court_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="training" {{ request('court_type') == 'training' ? 'selected' : '' }}>Training</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div class="w-full md:w-48">
                <select name="payment_status" class="input-base">
                    <option value="">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="pending_refund" {{ request('payment_status') == 'pending_refund' ? 'selected' : '' }}>Pending Refund</option>
                </select>
            </div>

            <!-- Search Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="btn-filled-tonal">
                    <span class="material-symbols-rounded">search</span>
                    Search
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
        <!-- Bulk Actions -->
        <div class="p-6 flex justify-end py-4 border-b border-neutral-200 bg-neutral-50">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2" x-data="{ isAllSelected: false }">
                    <input type="checkbox" id="select-all" class="" x-model="isAllSelected">
                    <label for="select-all" class="text-sm font-medium" x-text="isAllSelected ? 'Deselect All' : 'Select All'"></label>
                </div>
                <button id="change-status-btn" class="btn-filled-tonal text-sm" disabled>Change Status</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">
                            <input type="checkbox" class="row-select-all rounded border-neutral-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Court</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Payment Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="selected_bookings[]" value="{{ $booking->id }}" class="row-select rounded border-neutral-300">
                        </td>
                        <td class="px-6 py-4">
                            <p>{{ $booking->id }}</p>
                        </td>
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($booking->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($booking->payment_status === 'refunded') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-neutral-600">
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

<!-- Status Update Modal -->
<dialog id="status-modal" class="m-auto items-center justify-center bg-neutral-50 rounded-xl w-full max-w-lg">
    <div class="p-6 border-b border-neutral-200">
        <h3 class="text-lg font-semibold">Update Payment Status</h3>
    </div>

    <div class="p-6">
        <div class="mb-6">
            <label class="block text-sm font-medium text-neutral-700 mb-2">New Payment Status</label>
            <select id="modal-status-select" class="input-base">
                <option value="">Select Status</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>

        <div class="mb-6 space-y-2">
            <p class="font-medium text-neutral-600 text-sm">
                Selected Bookings
                (<span id="selected-list-count" class="font-medium text-neutral-700"></span>)
            </p>

            <div id="selected-bookings-list" class="max-h-48 overflow-y-auto border border-neutral-200 rounded-lg divide-y divide-neutral-200">
                <!-- Selected bookings will be populated here -->
            </div>
        </div>
    </div>

    <div class="p-6 border-t border-neutral-200 flex justify-end gap-3">
        <button id="cancel-status-update" class="btn-filled-tonal">Cancel</button>
        <button id="confirm-status-update" class="btn-filled" disabled>Update Status</button>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowSelectAllCheckbox = document.querySelector('.row-select-all');
        const rowCheckboxes = document.querySelectorAll('.row-select');
        const changeStatusBtn = document.getElementById('change-status-btn');
        const statusModal = document.getElementById('status-modal');
        const selectedListCount = document.getElementById('selected-list-count');
        const modalStatusSelect = document.getElementById('modal-status-select');
        const selectedBookingsList = document.getElementById('selected-bookings-list');
        const cancelStatusUpdate = document.getElementById('cancel-status-update');
        const confirmStatusUpdate = document.getElementById('confirm-status-update');

        function updateSelectAllState() {
            const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
            const someChecked = Array.from(rowCheckboxes).some(checkbox => checkbox.checked);

            selectAllCheckbox.checked = allChecked;
            rowSelectAllCheckbox.checked = allChecked;
            changeStatusBtn.disabled = !someChecked;
        }

        function updateSelectedBookingsList() {
            const selectedBookings = Array.from(rowCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => {
                    const row = checkbox.closest('tr');
                    const id = checkbox.value;
                    const name = row.querySelector('td:nth-child(3) .font-medium').textContent;
                    const date = row.querySelector('td:nth-child(5) .font-medium').textContent;
                    return {
                        id,
                        name,
                        date
                    };
                });

            selectedListCount.innerHTML = selectedBookings.length;
            selectedBookingsList.innerHTML = selectedBookings.map(booking => `
                <div class="p-3">
                    <div class="font-medium">${booking.name}</div>
                    <div class="text-sm text-neutral-600">${booking.date}</div>
                </div>
            `).join('');
        }

        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateSelectAllState();
        });

        rowSelectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateSelectAllState();
        });

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllState);
        });

        // Modal functionality
        changeStatusBtn.addEventListener('click', function() {
            updateSelectedBookingsList();
            statusModal.showModal();
        });

        cancelStatusUpdate.addEventListener('click', function() {
            statusModal.close();
            modalStatusSelect.value = '';
            confirmStatusUpdate.disabled = true;
        });

        modalStatusSelect.addEventListener('change', function() {
            confirmStatusUpdate.disabled = !this.value;
        });

        confirmStatusUpdate.addEventListener('click', function() {
            const selectedBookings = Array.from(rowCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const status = modalStatusSelect.value;

            // Submit the update
            fetch('{{ route("admin.bookings.bulkUpdatePayment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        bookings: selectedBookings,
                        payment_status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error updating payment status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating payment status');
                });
        });

        // Close modal when clicking outside
        statusModal.addEventListener('click', function(e) {
            if (e.target === statusModal) {
                cancelStatusUpdate.click();
            }
        });
    });
</script>
@endpush