@extends('layouts.admin')

@section('title', 'Edit Booking - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Edit Booking</h1>
            <p class="text-sm text-neutral-600">Update booking details</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn-outline flex items-center gap-2">
            <span class="material-symbols-rounded">arrow_back</span>
            Back to Bookings
        </a>
    </div>

    <div class="bg-white rounded-xl border border-neutral-200">
        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-neutral-700 mb-1">Player</label>
                    <select name="user_id" id="user_id" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                        <option value="">Select player</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->getFullNameAttribute() }} ({{ $user->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Court Selection -->
                <div>
                    <label for="court_id" class="block text-sm font-medium text-neutral-700 mb-1.5">Select Court</label>
                    <select name="court_id" id="court_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('court_id') border-red-500 @enderror">
                        <option value="">Select a court</option>
                        @foreach($courts as $court)
                        <option value="{{ $court->id }}"
                            {{ old('court_id', $booking->court_id) == $court->id ? 'selected' : '' }}
                            data-opening-time="{{ $court->formatted_opening_time }}"
                            data-closing-time="{{ $court->formatted_closing_time }}">
                            {{ $court->name }} ({{ ucfirst($court->type) }})
                        </option>
                        @endforeach
                    </select>
                    @error('court_id')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Booking Date -->
                <div>
                    <label for="booking_date" class="block text-sm font-medium text-neutral-700 mb-1.5">Booking Date</label>
                    <input type="date" name="booking_date" id="booking_date"
                        value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('booking_date') border-red-500 @enderror">
                    @error('booking_date')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Slots -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-neutral-700 mb-1.5">Start Time</label>
                            <select name="start_time" id="start_time" required
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('start_time') border-red-500 @enderror">
                                <option value="">Select start time</option>
                            </select>
                            @error('start_time')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-neutral-700 mb-1.5">End Time</label>
                            <select name="end_time" id="end_time" required
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('end_time') border-red-500 @enderror">
                                <option value="">Select end time</option>
                            </select>
                            @error('end_time')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p id="time-slot-info" class="text-sm text-neutral-600"></p>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-neutral-700 mb-1">Notes (optional)</label>
                    <textarea name="notes" id="notes" rows="2" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Additional notes...">{{ old('notes', $booking->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-neutral-200">
                <a href="{{ route('admin.bookings.index') }}" class="btn-text">Cancel</a>
                <button type="submit" class="btn-filled">Update Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courtSelect = document.getElementById('court_id');
        const startTimeSelect = document.getElementById('start_time');
        const endTimeSelect = document.getElementById('end_time');
        const dateInput = document.getElementById('booking_date');
        const timeSlotInfo = document.getElementById('time-slot-info');

        // Store the saved booking times
        const savedStartTime = '{{ $booking->start_time->format("H:i") }}';
        const savedEndTime = '{{ $booking->end_time->format("H:i") }}';

        console.log('Initial saved times:', {
            savedStartTime,
            savedEndTime
        });

        function updateTimeSlots() {
            const selectedOption = courtSelect.options[courtSelect.selectedIndex];
            if (!selectedOption.value) return;

            const openingTime = selectedOption.dataset.openingTime;
            const closingTime = selectedOption.dataset.closingTime;
            const selectedDate = new Date(dateInput.value);
            const isWeekend = selectedDate.getDay() === 0 || selectedDate.getDay() === 6;

            console.log('Updating time slots:', {
                openingTime,
                closingTime,
                selectedDate: selectedDate.toISOString(),
                isWeekend
            });

            // Clear existing options
            startTimeSelect.innerHTML = '<option value="">Select start time</option>';
            endTimeSelect.innerHTML = '<option value="">Select end time</option>';

            if (isWeekend) {
                // Weekend booking - fixed times
                const startOption = new Option('6:00 AM', '06:00');
                const endOption = new Option('10:00 PM', '22:00');
                startTimeSelect.add(startOption);
                endTimeSelect.add(endOption);
                timeSlotInfo.textContent = 'Weekend bookings are for the whole day';
            } else {
                // Weekday booking - dynamic times
                const start = new Date(`2000-01-01T${openingTime}`);
                const end = new Date(`2000-01-01T${closingTime}`);
                const current = new Date(start);

                console.log('Generating weekday slots:', {
                    start: start.toISOString(),
                    end: end.toISOString()
                });

                while (current < end) {
                    const timeValue = current.toTimeString().slice(0, 5);
                    const timeLabel = current.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });

                    // Add to start time options
                    const startOption = new Option(timeLabel, timeValue);
                    startTimeSelect.add(startOption);

                    // Add to end time options (excluding the last hour)
                    if (current < new Date(end - 3600000)) {
                        const endOption = new Option(timeLabel, timeValue);
                        endTimeSelect.add(endOption);
                    }

                    current.setHours(current.getHours() + 1);
                }
                timeSlotInfo.textContent = 'Select your preferred start and end times';
            }

            // After loading all options, set the saved times
            if (savedStartTime) {
                startTimeSelect.value = savedStartTime;
                console.log('Setting start time:', savedStartTime);
            }
            if (savedEndTime) {
                endTimeSelect.value = savedEndTime;
                console.log('Setting end time:', savedEndTime);
            }
        }

        // Update time slots when court is selected
        courtSelect.addEventListener('change', updateTimeSlots);

        // Update time slots when date changes
        dateInput.addEventListener('change', updateTimeSlots);

        // Initialize time slots if a court is already selected
        if (courtSelect.value) {
            console.log('Initial court selected:', courtSelect.value);
            updateTimeSlots();
        }
    });
</script>
@endpush