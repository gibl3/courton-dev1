@extends('layouts.player')

@section('title', 'Book a Court - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12">
    <!-- Page Header -->
    <section class="bg-white rounded-2xl shadow-lg p-8 flex justify-between items-center">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-3xl text-rose-600">
                        sports_tennis
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Book a Court</h1>
                    <p class="text-neutral-600">Select a court and time slot for your booking</p>
                </div>
            </div>
        </div>

        <!-- Court Filter and Search -->
        <div class="">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-500">
                            <span class="material-symbols-rounded">search</span>
                        </span>
                        <input type="text" id="court-search" placeholder="Search courts..." class="input-base pl-10">
                    </div>
                </div>
                <div class="flex gap-2">
                    <select class="input-base">
                        <option value="">All Types</option>
                        <option value="professional">Professional</option>
                        <option value="standard">Standard</option>
                        <option value="training">Training</option>
                    </select>
                    <select class="input-base">
                        <option value="">All Prices</option>
                        <option value="0-150">₱0 - ₱150</option>
                        <option value="151-200">₱151 - ₱200</option>
                        <option value="201+">₱201+</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Court Selection -->
        <section class="lg:col-span-2 space-y-8">
            <!-- Date and Time Selection -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Date Selection -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-neutral-700">Select Date</label>
                        <input type="date" id="date-picker" class="input-base w-full">
                    </div>

                    <!-- Time Slots Selection -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Start Time -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700">Start Time</label>
                                <select id="start-time" class="input-base w-full">
                                    <option value="">Select start time</option>
                                    @php
                                    $testDate = request()->query('test_date');
                                    $today = $testDate ? \Carbon\Carbon::parse(time: $testDate) : \Carbon\Carbon::now();
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
                            </div>

                            <!-- End Time -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700">End Time</label>
                                <select id="end-time" class="input-base w-full">
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
                            </div>
                        </div>
                        <p class="text-sm text-neutral-500">
                            @if($isWeekend)
                            Weekend bookings are for the whole day
                            @else
                            Select your preferred start and end times
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Court Cards -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[600px] overflow-y-auto pr-2">
                    @foreach($courts as $court)
                    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden group hover:shadow-lg transition-all" data-court-id="{{ $court->id }}">
                        <div class="relative h-48">
                            <x-cloudinary::image
                                public-id="{{ $court->image_path }}"
                                alt="{{ $court->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                fetch-format="auto"
                                quality="auto" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-sm font-semibold">{{ $court->name }}</h3>
                                <p class="text-sm text-neutral-200">{{ ucfirst($court->type) }} Court</p>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Price and Capacity -->
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-2xl font-bold text-rose-600">₱{{ number_format($court->rate_per_hour, 2) }}</span>
                                    <span class="text-sm text-neutral-500">Weekday: per hour</span>
                                    <span class="text-sm text-neutral-500">Weekend: whole day</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-medium text-neutral-700">Max Capacity</span>
                                    <span class="text-lg font-semibold text-neutral-800">4 Players</span>
                                </div>
                            </div>

                            <!-- Availability -->
                            <div class="bg-neutral-50 rounded-lg">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-neutral-700">Operating Hours</span>
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $court->is_available_today ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $court->is_available_today ? 'Available' : 'Closed' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-neutral-600">{{ $court->today_availability }}</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button
                                class="btn-filled w-full select-court-btn"
                                data-court='@json($court)'>
                                Select Court
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Booking Summary -->
        <section class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-8">
                <h2 class="text-xl font-bold mb-6">Booking Summary</h2>

                <div class="space-y-6">
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
                    <button class="btn-filled w-full" id="confirm-button">
                        Confirm Booking
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('focus') === 'search') {
            document.getElementById('court-search').focus();
        }
    });
</script>
@vite(['resources/js/booking/booking.js'])
@endpush