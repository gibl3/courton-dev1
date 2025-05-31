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
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-shadow">
            <div class="relative h-64">
                @if($court->image_path)
                <x-cloudinary::image
                    public-id="{{ $court->image_path }}"
                    alt="{{ $court->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    width="800"
                    height="600"
                    crop="fill"
                    fetch-format="auto"
                    quality="auto" />
                @else
                <div class="w-full h-full bg-neutral-200 flex items-center justify-center">
                    <span class="material-symbols-rounded text-4xl text-neutral-400">image</span>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="text-xl font-semibold">{{ $court->name }}</h3>
                    <p class="text-sm text-neutral-200">{{ ucfirst($court->type) }} Court</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-x-2 text-rose-600">
                    <span class="material-symbols-rounded">schedule</span>
                    <span>{{ $court->opening_time->format('g:i A') }} - {{ $court->closing_time->format('g:i A') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-x-2 text-rose-600">
                        <span class="material-symbols-rounded">payments</span>
                        <span>₱{{ number_format($court->rate_per_hour, 2) }}</span>
                    </div>
                    <div class="flex flex-col text-sm text-neutral-600 ml-8">
                        <span>Weekday: per hour</span>
                        <span>Weekend: whole day</span>
                    </div>
                </div>
                <p class="text-neutral-600">{{ $court->description }}</p>
                <a href="{{ route('player.bookings.index') }}" class="btn-filled w-full justify-center">Book Now</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection