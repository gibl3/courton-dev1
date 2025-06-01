@extends('layouts.guest')

@section('title', 'Welcome to Courton')

@section('content')
<div class="mt-32 space-y-32">
    <!-- HOME -->
    <section class="relative flex justify-center h-96 rounded-4xl bg-[url('/public/images/hero-bg.jpg')] bg-cover bg-center">
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-900/70 to-neutral-900/0 backdrop-blur-[4px] rounded-4xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col gap-y-8 items-center justify-center">
            <div class="flex flex-col items-center gap-6 text-neutral-50">
                <div class="flex flex-col items-center gap-2">
                    <a href="https://maps.app.goo.gl/kEfPXqaSkzZq7VNP7" target="_blank" class="flex items-center gap-x-1 text-sm size-fit bg-neutral-500/50 py-2 px-4 rounded-full">
                        <span class="material-symbols-rounded">
                            pin_drop
                        </span>
                        Brgy. Linao Indoor Badminton Court
                    </a>

                    <h1 class="text-8xl font-bold">Game on. Court ready.</h1>
                </div>

                <p class="text-center max-w-2xl">Experience the perfect blend of comfort and performance in our state-of-the-art badminton courts. Book now and enjoy our exclusive promotions!</p>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('player.bookings.index') }}" class="btn-filled pl-6 pr-[16px]">
                    Book now
                    <span class="material-symbols-rounded md-icon-24">
                        chevron_right
                    </span>
                </a>
                <a href="#promo-section" class="btn-filled-tonal pl-6 pr-[16px]">
                    View Promos
                    <span class="material-symbols-rounded md-icon-24">
                        local_offer
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- PROMO SECTION -->
    <section class="flex flex-col gap-y-12 py-16" id="promo-section">
        <div class="text-center space-y-4">
            <h2 class="text-4xl font-bold">Weekend Special</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">Enjoy unlimited badminton fun with our exclusive weekend whole day pass!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-8">
            <!-- Main Promo -->
            <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden group hover:bg-neutral-50 transition-colors">
                <div class="relative h-64">
                    <img src="/images/weekend-promo.jpeg" alt="" class="size-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <span class="px-3 py-1 bg-rose-600 rounded-full text-sm font-medium">Weekend Special</span>
                        <h3 class="text-2xl font-bold mt-2">Whole Day Pass</h3>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold text-rose-600">₱100</span>
                        <span class="text-neutral-600">/day</span>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-x-3">
                            <span class="material-symbols-rounded text-rose-600">check_circle</span>
                            <span>Unlimited Court Access</span>
                        </li>
                        <li class="flex items-center gap-x-3">
                            <span class="material-symbols-rounded text-rose-600">check_circle</span>
                            <span>All Court Types Available</span>
                        </li>
                        <li class="flex items-center gap-x-3">
                            <span class="material-symbols-rounded text-rose-600">check_circle</span>
                            <span>Valid for Weekends Only</span>
                        </li>
                    </ul>
                    <a href="{{ route('player.bookings.index') }}" class="btn-filled w-full justify-center">Book Now</a>
                </div>
            </div>

            <!-- Facility Features -->
            <div x-data="{
  features: [
    {
      icon: 'sports_tennis',
      title: 'Premium Courts',
      description: 'Professional-grade courts with premium flooring and lighting'
    },
    {
      icon: 'shower',
      title: 'Clean Facilities',
      description: 'Well-maintained showers, changing rooms, and rest areas'
    },
    {
      icon: 'inventory_2',
      title: 'Equipment Available',
      description: 'Rackets and shuttlecocks available for rent'
    }
  ]
}">
                <div class="space-y-6">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="bg-white rounded-xl border border-neutral-200 p-6 hover:bg-neutral-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="size-12 rounded-xl bg-rose-100 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-2xl text-rose-600" x-text="feature.icon"></span>
                                </div>
                                <div>
                                    <h3 class="font-semibold" x-text="feature.title"></h3>
                                    <p class="text-sm text-neutral-600" x-text="feature.description"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
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
            <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden group hover:bg-neutral-50 transition-colors">
                <div class="relative h-64">
                    <x-cloudinary::image
                        public-id="{{ $court->image_path }}"
                        alt="{{ $court->name }}"
                        class="size-full object-cover"
                        fetch-format="auto"
                        quality="auto" />
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
                    <a href="{{ route('player.bookings.index') }}" class="btn-filled w-full justify-center">Book Now</a>
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
    <section
        x-data="{
    courts: [
      {
        title: 'Standard Courts',
        price: 150,
        description: 'Perfect for casual and recreational players',
        features: ['Quality Flooring', 'Adequate Lighting', 'Basic Amenities'],
        popular: false
      },
      {
        title: 'Professional Courts',
        price: 200,
        description: 'For competitive and serious players',
        features: ['Premium Flooring', 'Professional Lighting', 'Climate Control', 'Extended Hours'],
        popular: true
      },
      {
        title: 'Training Courts',
        price: 180,
        description: 'Ideal for training and coaching sessions',
        features: ['Professional Setup', 'Training Equipment', 'Coach-Friendly', 'Video Recording'],
        popular: false
      }
    ]
  }"
        class="flex flex-col gap-y-16 py-16 bg-neutral-100/50"
        id="pricing-section">
        <div class="text-center space-y-4">
            <h2 class="text-4xl font-bold">Court Types</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">Choose the perfect court that matches your playing style and preferences.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-8">
            <template x-for="court in courts" :key="court.title">
                <div
                    class="bg-white rounded-xl border border-neutral-200 p-8 flex flex-col relative"
                    :class="{'relative': court.popular}">
                    <!-- Popular badge -->
                    <div
                        x-show="court.popular"
                        class="absolute -top-4 left-1/2 -translate-x-1/2 bg-rose-600 text-white px-4 py-1 rounded-full text-sm"
                        x-cloak>
                        Most Popular
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold" x-text="court.title"></h3>
                        <div class="flex items-baseline gap-x-1">
                            <span class="text-4xl font-bold" x-text="`₱${court.price}`"></span>
                            <span class="text-neutral-600">/hour</span>
                        </div>
                        <p class="text-neutral-600" x-text="court.description"></p>
                    </div>

                    <div class="my-8 space-y-4">
                        <template x-for="feature in court.features" :key="feature">
                            <div class="flex items-center gap-x-3">
                                <span class="material-symbols-rounded text-rose-600">check_circle</span>
                                <span x-text="feature"></span>
                            </div>
                        </template>
                    </div>

                    <a href="{{ route('courts.index') }}" class="btn-filled-tonal mt-auto">View Courts</a>
                </div>
            </template>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="flex flex-col gap-y-12 py-16" x-data="{
    features: [
        {
            icon: 'sports_tennis',
            title: 'Professional Courts',
            description: 'State-of-the-art courts with premium flooring and lighting'
        },
        {
            icon: 'schedule',
            title: 'Flexible Hours',
            description: 'Open early morning until late evening for your convenience'
        },
        {
            icon: 'security',
            title: 'Secure Facility',
            description: '24/7 security and CCTV surveillance for your safety'
        },
        {
            icon: 'support_agent',
            title: 'Dedicated Support',
            description: 'Professional staff ready to assist you at all times'
        }
    ]
}">
        <div class="text-center space-y-4">
            <h2 class="text-4xl font-bold">Why Choose Us?</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">
                Experience the best badminton facilities with our premium amenities and services.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-8">
            <template x-for="(feature, index) in features" :key="index">
                <div class="bg-white rounded-xl border border-neutral-200 p-6 text-center">
                    <div class="size-16 rounded-xl bg-rose-100 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-rounded text-3xl text-rose-600" x-text="feature.icon"></span>
                    </div>
                    <h3 class="font-semibold mb-2" x-text="feature.title"></h3>
                    <p class="text-sm text-neutral-600" x-text="feature.description"></p>
                </div>
            </template>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-neutral-200">
        <div class="container mx-auto px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="size-10 rounded-xl bg-rose-100 flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl text-rose-600">sports_tennis</span>
                        </div>
                        <h3 class="text-xl font-bold">Courton</h3>
                    </div>
                    <p class="text-sm text-neutral-600">Experience the perfect blend of comfort and performance in our state-of-the-art badminton courts.</p>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h4 class="font-semibold">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('player.bookings.index') }}" class="text-neutral-600 hover:text-rose-600 transition-colors">Book a Court</a></li>
                        <li><a href="#court-section" class="text-neutral-600 hover:text-rose-600 transition-colors">Our Courts</a></li>
                        <li><a href="#pricing-section" class="text-neutral-600 hover:text-rose-600 transition-colors">Pricing</a></li>
                        <li><a href="#promo-section" class="text-neutral-600 hover:text-rose-600 transition-colors">Promotions</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="space-y-4">
                    <h4 class="font-semibold">Contact Us</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2 text-neutral-600">
                            <div class="size-8 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-600">location_on</span>
                            </div>
                            <a href="https://maps.app.goo.gl/kEfPXqaSkzZq7VNP7" target="_blank" class="hover:text-rose-600 transition-colors">Brgy. Linao Indoor Badminton Court</a>
                        </li>
                        <li class="flex items-center gap-2 text-neutral-600">
                            <div class="size-8 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-600">schedule</span>
                            </div>
                            <span>6:00 AM - 10:00 PM</span>
                        </li>
                        <li class="flex items-center gap-2 text-neutral-600">
                            <div class="size-8 rounded-lg bg-rose-100 flex items-center justify-center">
                                <span class="material-symbols-rounded text-rose-600">phone</span>
                            </div>
                            <a href="tel:+639123456789" class="hover:text-rose-600 transition-colors">+63 912 345 6789</a>
                        </li>
                    </ul>
                </div>

                <!-- Social -->
                <div class="space-y-4">
                    <h4 class="font-semibold">Follow Us</h4>
                    <div class="flex gap-4">
                        <a href="#" class="size-10 rounded-lg bg-rose-100 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors">
                            <span class="material-symbols-rounded text-rose-600 group-hover:text-white">facebook</span>
                        </a>
                        <a href="#" class="size-10 rounded-lg bg-rose-100 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors">
                            <span class="material-symbols-rounded text-rose-600 group-hover:text-white">mail</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-12 pt-8 border-t border-neutral-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-neutral-600">&copy; {{ date('Y') }} Courton. All rights reserved.</p>
                    <div class="flex gap-4 text-sm">
                        <a href="#" class="text-neutral-600 hover:text-rose-600 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-neutral-600 hover:text-rose-600 transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endpush