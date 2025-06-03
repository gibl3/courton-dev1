@extends('layouts.guest')

@section('title', 'All Courts - Courton')

@section('content')
<div class="py-16">
    <div class="text-center space-y-4 mb-16">
        <h1 class="text-4xl font-bold">All Courts</h1>
        <p class="text-neutral-600 max-w-2xl mx-auto">Browse through our complete collection of badminton courts.</p>
    </div>

    <!-- Court Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-8">
        @foreach($courts as $court)
        <div class="court-card bg-white rounded-xl border border-neutral-200 overflow-hidden group hover:bg-neutral-50 transition-colors"
            x-data="{ showDescription: false }">
            <div class="relative h-48">
                @if($court->image_path)
                <x-cloudinary::image
                    public-id="{{ $court->image_path }}"
                    alt="{{ $court->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    fetch-format="auto"
                    quality="auto" />
                @else
                <div class="w-full h-full bg-neutral-200 flex items-center justify-center">
                    <span class="material-symbols-rounded text-4xl text-neutral-400">image</span>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="text-sm font-semibold">{{ $court->name }}</h3>
                    <p class="text-sm text-neutral-200">{{ ucfirst($court->type) }} Court</p>
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

                <!-- Court Description -->
                <div x-show="showDescription"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-neutral-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-neutral-700">About this court</h4>
                        <button @click="showDescription = false" class="btn-base p-0 text-neutral-400 hover:text-neutral-600">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>
                    <p class="text-sm text-neutral-600">{{ $court->description }}</p>
                </div>

                <!-- Action Button -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('player.bookings.index') }}" class="btn-filled flex-1 justify-center">
                        Book Now
                    </a>
                    <button @click="showDescription = !showDescription"
                        class="p-2 rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-600 transition-colors">
                        <span class="material-symbols-rounded">info</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection