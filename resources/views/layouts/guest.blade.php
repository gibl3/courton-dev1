<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    <title>@yield('title', 'Welcome to Courton')</title>
</head>

<body class="flex flex-col min-h-screen px-4 md:px-12 lg:px-36 py-2 md:py-4 bg-neutral-50 text-neutral-900 font-normal">
    <header class="flex flex-col md:flex-row justify-between items-center gap-y-2 md:gap-y-0">
        <div class="flex-1 flex flex-col items-start">
            <a href="{{ route('welcome') }}" class="text-xl font-semibold">Courton</a>
            <p class="text-xs">Badminton Court Booking System</p>
        </div>

        @if(request()->routeIs('welcome'))
        <!-- Mobile Nav -->
        <div class="md:hidden flex-1 flex justify-end">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <span class="material-symbols-rounded text-2xl">menu</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg z-50">
                    <ul class="flex flex-col py-2">
                        <li><a href="{{ route('welcome') }}" class="block px-4 py-2 hover:bg-neutral-100">Home</a></li>
                        <li><a href="#court-section" class="block px-4 py-2 hover:bg-neutral-100">Court</a></li>
                        <li><a href="#pricing-section" class="block px-4 py-2 hover:bg-neutral-100">Pricing</a></li>
                        <li class="border-t mt-2"><a href="{{ route('auth.login') }}" class="block px-4 py-2 hover:bg-neutral-100">Login</a></li>
                        <li><a href="{{ route('auth.create') }}" class="block px-4 py-2 hover:bg-neutral-100">Signup</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Desktop Nav -->
        <nav class="hidden md:flex flex-1 justify-center">
            <ul class="flex flex-row gap-x-6">
                <li><a href="{{ route('welcome') }}" class="btn-base hover:text-neutral-900/70">Home</a></li>
                <li><a href="#court-section" class="btn-base hover:text-neutral-900/70">Court</a></li>
                <li><a href="#pricing-section" class="btn-base hover:text-neutral-900/70">Pricing</a></li>
            </ul>
        </nav>

        <div class="hidden md:flex flex-1 justify-end gap-x-2.5">
            <a href="{{ route('auth.login') }}" class="btn-filled-tonal">Login</a>
            <a href="{{ route('auth.create') }}" class="btn-filled">Signup</a>
        </div>
        @else
        <div class="flex-1 flex justify-end w-full">
            <a href="{{ route('welcome') }}" class="btn-base hover:text-neutral-900/70 flex items-center gap-x-1">
                <span class="material-symbols-rounded">
                    arrow_back
                </span>
                Back to Home
            </a>
        </div>
        @endif
    </header>

    <main class="flex flex-1 flex-col mt-4 md:mt-8">
        @yield('content')
    </main>
</body>

</html>