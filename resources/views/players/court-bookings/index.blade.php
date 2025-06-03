@extends('layouts.player')

@section('title', 'Book a Court - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12" x-data="{
        selectedCourtId: null,
        hasCourts: {{ $courts->count() > 0 ? 'true' : 'false' }}
    }">
    <!-- Page Header -->
    <section class="bg-white rounded-xl border border-neutral-200 p-8 flex justify-between items-center">
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
                        <input type="text" id="court-search" placeholder="Search courts..." class="input-base pl-10"
                            :disabled="!hasCourts">
                    </div>
                </div>
                <div class="flex gap-2">
                    <select id="court-type-filter" class="input-base" :disabled="!hasCourts">
                        <option value="">All Types</option>
                        <option value="professional">Professional</option>
                        <option value="standard">Standard</option>
                        <option value="training">Training</option>
                    </select>
                    <select id="court-price-filter" class="input-base" :disabled="!hasCourts">
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
        <section class="lg:col-span-2 space-y-6">
            <!-- Date and Time Selection -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6" x-show="selectedCourtId" x-transition
                x-cloak>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Date Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">Select Date</label>
                        <input type="date" id="date-picker" class="input-base w-full">
                    </div>

                    <!-- Time Slots Selection -->
                    <div class="space-y-4 col-span-2">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Start Time -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-neutral-700">Start Time</label>
                                <select id="start-time" class="input-base w-full">
                                    <option value="">Select start time</option>
                                </select>
                            </div>

                            <!-- End Time -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-neutral-700">End Time</label>
                                <select id="end-time" class="input-base w-full">
                                    <option value="">Select end time</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-sm text-neutral-500 col-span-2 text-end">
                            <span id="weekend-message" class="hidden text-rose-600 font-medium">Weekend booking: Whole
                                day (6 AM - 10 PM)</span>
                            <span id="weekday-message">Select your preferred start and end times</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Court Cards -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[600px] overflow-y-auto pr-2" id="court-cards">
                    @foreach ($courts as $court)
                    <div class="court-card bg-white rounded-xl border border-neutral-200 overflow-hidden group hover:bg-neutral-50 transition-colors"
                        data-type="{{ $court->type }}" data-price="{{ $court->rate_per_hour }}">
                        <div class="relative h-48">
                            <x-cloudinary::image public-id="{{ $court->image_path }}" alt="{{ $court->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                fetch-format="auto" quality="auto" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-sm font-semibold">{{ $court->name }}</h3>
                                <p class="text-sm text-neutral-200">{{ $court->type }} Court</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Price and Capacity -->
                            <div class="flex items-start justify-between">
                                <div class="flex flex-col">
                                    <span
                                        class="text-2xl font-bold text-rose-600">₱{{ number_format($court->rate_per_hour, 2) }}</span>
                                    <p class="text-sm text-neutral-500">
                                        Weekend:
                                        <span
                                            class="text-neutral-700 font-medium">₱{{ number_format($court->weekend_rate_per_hour, 2) }}</span>
                                    </p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-lg font-semibold text-neutral-800">4 Players</span>
                                    <span class="text-sm text-neutral-500">Max Capacity</span>
                                </div>
                            </div>

                            <!-- Availability -->
                            <div class="bg-neutral-50 rounded-lg">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-neutral-700">Operating Hours</span>
                                        <span
                                            class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">{{ ucfirst($court->status) }}</span>
                                    </div>
                                    <p class="text-sm text-neutral-600">{{ $court->today_availability }}</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button class="w-full select-court-btn"
                                :class="selectedCourtId === {{ $court->id }} ?
                                            'btn-filled-tonal disabled:bg-rose-100 disabled:text-rose-600 disabled:hover:shadow-none' :
                                            'btn-filled'"
                                data-court='@json($court)'
                                :disabled="selectedCourtId === {{ $court->id }}"
                                @click="selectedCourtId = {{ $court->id }}">
                                <span
                                    x-text="selectedCourtId === {{ $court->id }} ? 'Selected' : 'Select Court'"
                                    :class="selectedCourtId === {{ $court->id }} ? 'font-medium' : ''">
                                </span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    <div id="no-results" class="col-span-3 text-center text-neutral-600 hidden">
                        <span class="material-symbols-rounded md-icon-36 text-neutral-500">
                            search_off
                        </span>
                        <p>No courts found matching your criteria</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking Summary -->
        <section class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-neutral-200 p-8 sticky top-8">
                <h2 class="text-xl font-bold mb-6">Booking Summary</h2>

                <div class="space-y-6">
                    <!-- Selected Court -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Selected Court</label>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-neutral-50" id="court-summary">
                            <div class="size-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-3xl text-rose-600">
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
                    <button class="btn-filled w-full" id="confirm-button" :disabled="!hasCourts">
                        Confirm Booking
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add Cloudinary configuration
    window.cloudinaryConfig = {
        cloudName: '{{ config('
        cloudinary.cloud_name ') }}'
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('court-search');
        const typeFilter = document.getElementById('court-type-filter');
        const priceFilter = document.getElementById('court-price-filter');
        const courtCards = document.querySelectorAll('.court-card');
        const noResults = document.getElementById('no-results');

        function filterCourts() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = typeFilter.value;
            const selectedPrice = priceFilter.value;
            let visibleCount = 0;

            courtCards.forEach(card => {
                const type = card.dataset.type;
                const price = parseFloat(card.dataset.price);
                const name = card.querySelector('h3').textContent.toLowerCase();
                const typeText = card.querySelector('p').textContent.toLowerCase();

                const matchesSearch = name.includes(searchTerm) || typeText.includes(searchTerm);
                const matchesType = !selectedType || type === selectedType;
                const matchesPrice = !selectedPrice || matchesPriceRange(price, selectedPrice);

                if (matchesSearch && matchesType && matchesPrice) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        function matchesPriceRange(price, range) {
            if (!range) return true;

            const [min, max] = range.split('-').map(Number);
            if (range === '201+') {
                return price >= 201;
            }
            return price >= min && price <= max;
        }

        searchInput.addEventListener('input', filterCourts);
        typeFilter.addEventListener('change', filterCourts);
        priceFilter.addEventListener('change', filterCourts);

        // Focus search if URL parameter is set
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('focus') === 'search') {
            searchInput.focus();
        }
    });
</script>
@vite(['resources/js/booking/booking.js'])
@endpush