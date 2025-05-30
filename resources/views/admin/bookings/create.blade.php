@extends('layouts.admin')

@section('title', 'Create Booking - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Create Booking</h1>
            <p class="text-sm text-neutral-600">Book a court for a user</p>
        </div>
        <a href="{{ route('admin.bookings') }}" class="btn-outline flex items-center gap-2">
            <span class="material-symbols-rounded">arrow_back</span>
            Back to Bookings
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2 space-y-8">
            <form action="{{ route('admin.bookings.store') }}" id="booking-form" method="POST" class="bg-white rounded-xl border border-neutral-200 p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Search -->
                    <div class="relative">
                        <label for="user-search" class="block text-sm font-medium text-neutral-700 mb-1">Player</label>
                        <input type="text" id="user-search" name="user_search" autocomplete="off" placeholder="Search by name or email..."
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('user_id') border-red-500 @enderror">
                        <input type="hidden" name="user_id" id="user-id" value="{{ old('user_id') }}">
                        <div id="user-search-results" class="bg-white border border-neutral-200 rounded-lg mt-1 shadow-lg z-10 absolute w-full hidden">
                            <template id="user-result-template">
                                <div class="p-2 cursor-pointer hover:bg-rose-50 border-b border-neutral-100 last:border-0">
                                    <div class="font-medium user-name"></div>
                                    <div class="text-xs text-neutral-500 user-email"></div>
                                </div>
                            </template>
                        </div>
                        @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Court Selection -->
                    <div>
                        <label for="court-id" class="block text-sm font-medium text-neutral-700 mb-1">Court</label>
                        <select name="court_id" id="court-id" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('court_id') border-red-500 @enderror">
                            <option value="">Select court</option>
                            @foreach($courts as $court)
                            <option value="{{ $court->id }}" {{ old('court_id') == $court->id ? 'selected' : '' }} data-court='@json($court)'>{{ $court->name }} ({{ ucfirst($court->type) }})</option>
                            @endforeach
                        </select>
                        @error('court_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Booking Date -->
                    <div>
                        <label for="booking-date" class="block text-sm font-medium text-neutral-700 mb-1">Booking Date</label>
                        <input type="date" name="booking_date" id="booking-date" value="{{ old('booking_date') }}" min="{{ now()->toDateString() }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('booking_date') border-red-500 @enderror">
                        @error('booking_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Time Slots Selection -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Start Time -->
                            <div>
                                <label for="start-time" class="block text-sm font-medium text-neutral-700 mb-1">Start Time</label>
                                <select name="start_time" id="start-time" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('start_time') border-red-500 @enderror">
                                    <option value="">Select start time</option>
                                    @php
                                    $today = \Carbon\Carbon::now();
                                    $isWeekend = $today->isWeekend();

                                    if ($isWeekend) {
                                    echo '<option value="06:00">6:00 AM</option>';
                                    } else {
                                    $start = \Carbon\Carbon::parse($courts->first()->opening_time);
                                    $end = \Carbon\Carbon::parse($courts->first()->closing_time)->subHour();
                                    $current = $start->copy();

                                    while($current->lt($end)) {
                                    echo '<option value="' . $current->format('H:i') . '">' .
                                        $current->format('g:i A') .
                                        '</option>';
                                    $current->addHour();
                                    }
                                    }
                                    @endphp
                                </select>
                                @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end-time" class="block text-sm font-medium text-neutral-700 mb-1">End Time</label>
                                <select name="end_time" id="end-time" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('end_time') border-red-500 @enderror">
                                    <option value="">Select end time</option>
                                    @php
                                    if ($isWeekend) {
                                    echo '<option value="22:00">10:00 PM</option>';
                                    } else {
                                    $start = \Carbon\Carbon::parse($courts->first()->opening_time)->addHour();
                                    $end = \Carbon\Carbon::parse($courts->first()->closing_time);
                                    $current = $start->copy();

                                    while($current->lte($end)) {
                                    echo '<option value="' . $current->format('H:i') . '">' .
                                        $current->format('g:i A') .
                                        '</option>';
                                    $current->addHour();
                                    }
                                    }
                                    @endphp
                                </select>
                                @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="text-sm text-neutral-500" id="time-slot-info">
                            @if($isWeekend)
                            Weekend bookings are for the whole day
                            @else
                            Select your preferred start and end times
                            @endif
                        </p>
                    </div>
                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-neutral-700 mb-1">Notes (optional)</label>
                        <textarea name="notes" id="notes" rows="2" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('notes') border-red-500 @enderror" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        <!-- Booking Summary -->
        <section class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-8">
                <h2 class="text-xl font-bold mb-6">Booking Summary</h2>

                <div class="space-y-6">
                    <!-- Selected Player -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Selected Player</label>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-neutral-50" id="player-summary">
                            <div class="size-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-600">
                                    person
                                </span>
                            </div>
                            <div>
                                <p class="font-medium" id="player-name">No player selected</p>
                                <p class="text-sm text-neutral-600" id="player-email">Select a player to continue</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Court -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Selected Court</label>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-neutral-50" id="court-summary">
                            <div class="size-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-600">
                                    sports_tennis
                                </span>
                            </div>
                            <div>
                                <p class="font-medium" id="court-name">No court selected</p>
                                <p class="text-sm text-neutral-600" id="court-type">Select a court to continue</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Selected Date</label>
                        <div class="p-3 rounded-lg bg-neutral-50" id="date-summary">
                            <p class="font-medium">Not selected</p>
                        </div>
                    </div>

                    <!-- Selected Time -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Selected Time Slot</label>
                        <div class="p-3 rounded-lg bg-neutral-50" id="time-summary">
                            <p class="font-medium">Not selected</p>
                            <p class="text-sm text-neutral-500 hidden" id="time-details"></p>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="pt-6 border-t border-neutral-200">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-neutral-600">Price per hour</p>
                            <span class="font-medium" id="price-per-hour">₱0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span class="text-rose-600" id="total-price">₱0.00</span>
                        </div>
                    </div>

                    <!-- Book Button -->
                    <button type="submit" form="booking-form" class="btn-filled w-full flex items-center gap-2" id="submit-booking">
                        <span class="material-symbols-rounded">add</span>
                        Create Booking
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/booking/admin-booking.js'])
@endpush