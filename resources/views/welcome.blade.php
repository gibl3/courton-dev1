@extends('layouts.guest')

@section('title', 'Welcome to Courton')

@section('content')
<div class="mt-32 space-y-32">
    <!-- HOME -->
    <section class="relative flex justify-center h-96 rounded-4xl bg-[url('/public/images/hero-bg.jpg')] bg-cover bg-center">
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-900/40 to-neutral-900/0 backdrop-blur-[4px] rounded-4xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col gap-y-8 items-center justify-center">
            <div class="flex flex-col gap-6  text-neutral-50">
                <div class="flex flex-col items-center gap-2">
                    <a href="https://maps.app.goo.gl/kEfPXqaSkzZq7VNP7" target="_blank" class="flex items-center gap-x-1 text-sm size-fit bg-neutral-500/50 py-2 px-4 rounded-full">
                        <span class="material-symbols-rounded">
                            pin_drop
                        </span>
                        Brgy. Linao Indoor Badminton Court
                    </a>

                    <h1 class="text-8xl font-bold">Game on. Court ready.</h1>
                </div>

                <p class="text-center">Lorem ipsum dolor sit amet consectetur, adipisicing elit. In voluptatum deleniti voluptate.</p>
            </div>

            <div class="flex">
                <a href="" class="btn-filled pl-6 pr-[16px]">
                    Book now
                    <span class="material-symbols-rounded md-icon-24">
                        chevron_right
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- COURT -->
    <section class="flex flex-col gap-y-12 py-16" id="court-section">
        <div class="text-center space-y-4">
            <h2 class="text-4xl font-bold">Our Courts</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">Experience the perfect blend of comfort and performance in our state-of-the-art badminton courts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-8">
            @foreach($featuredCourts as $court)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-shadow">
                <div class="relative h-64">
                    <img src="{{ $court->image_path }}" alt="{{ $court->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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
                    <p class="text-neutral-600">{{ $court->description }}</p>
                    <a href="{{ route('player.book') }}" class="btn-filled w-full justify-center">Book Now</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center">
            <a href="{{ route('courts.index') }}" class="btn-filled-tonal px-8 py-3 flex items-center gap-x-2">
                View More Courts
                <span class="material-symbols-rounded">
                    arrow_forward
                </span>
            </a>
        </div>
    </section>

    <!-- PRICING -->
    <section class="flex flex-col gap-y-16 py-16 bg-neutral-100/50" id="pricing-section">
        <div class="text-center space-y-4">
            <h2 class="text-4xl font-bold">Court Types</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">Choose the perfect court that matches your playing style and preferences.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-8">
            <!-- Standard Courts -->
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col">
                <div class="space-y-4">
                    <h3 class="text-2xl font-semibold">Standard Courts</h3>
                    <div class="flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold">₱150</span>
                        <span class="text-neutral-600">/hour</span>
                    </div>
                    <p class="text-neutral-600">Perfect for casual and recreational players</p>
                </div>

                <div class="my-8 space-y-4">
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Quality Flooring</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Adequate Lighting</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Basic Amenities</span>
                    </div>
                </div>

                <a href="{{ route('courts.index') }}" class="btn-filled mt-auto">View Courts</a>
            </div>

            <!-- Professional Courts -->
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col relative">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-rose-600 text-white px-4 py-1 rounded-full text-sm">
                    Most Popular
                </div>
                <div class="space-y-4">
                    <h3 class="text-2xl font-semibold">Professional Courts</h3>
                    <div class="flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold">₱200</span>
                        <span class="text-neutral-600">/hour</span>
                    </div>
                    <p class="text-neutral-600">For competitive and serious players</p>
                </div>

                <div class="my-8 space-y-4">
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Premium Flooring</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Professional Lighting</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Climate Control</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Extended Hours</span>
                    </div>
                </div>

                <a href="{{ route('courts.index') }}" class="btn-filled mt-auto">View Courts</a>
            </div>

            <!-- Training Courts -->
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col">
                <div class="space-y-4">
                    <h3 class="text-2xl font-semibold">Training Courts</h3>
                    <div class="flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold">₱180</span>
                        <span class="text-neutral-600">/hour</span>
                    </div>
                    <p class="text-neutral-600">Ideal for training and coaching sessions</p>
                </div>

                <div class="my-8 space-y-4">
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Professional Setup</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Training Equipment</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Coach-Friendly</span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="material-symbols-rounded text-rose-600">check_circle</span>
                        <span>Video Recording</span>
                    </div>
                </div>

                <a href="{{ route('courts.index') }}" class="btn-filled mt-auto">View Courts</a>
            </div>
        </div>
    </section>
</div>
@endsection