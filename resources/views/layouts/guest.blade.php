<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('scripts')
    @vite('resources/css/app.css')
    <title>@yield('title', 'Welcome to Courton')</title>
</head>

<body class="flex flex-col h-screen px-36 py-4 bg-neutral-50 text-neutral-900 font-normal">
    <header class="flex flex-row justify-between items-center">
        <div class="flex-1 flex flex-col">
            <a href="{{ route('welcome') }}" class="text-xl font-semibold">Courton</a>
            <p class="text-xs">Badminton Court Booking System</p>
        </div>

        @if(request()->routeIs('welcome'))
        <nav class="flex-1 flex justify-center">
            <ul class="flex flex-row gap-x-6">
                <li><a href="{{ route('welcome') }}" class="btn-base hover:text-neutral-900/70">Home</a></li>
                <li><a href="#court-section" class="btn-base hover:text-neutral-900/70">Court</a></li>
                <li><a href="#pricing-section" class="btn-base hover:text-neutral-900/70">Pricing</a></li>
            </ul>
        </nav>

        <div class="flex-1 flex justify-end gap-x-2.5">
            <a href="{{ route('auth.login') }}" class="btn-filled-tonal">Login</a>
            <a href="{{ route('auth.create') }}" class="btn-filled">Signup</a>
        </div>
        @else
        <div class="flex-1 flex justify-end">
            <a href="{{ route('welcome') }}" class="btn-base hover:text-neutral-900/70 flex items-center gap-x-1">
                <span class="material-symbols-rounded">
                    arrow_back
                </span>
                Back to Home
            </a>
        </div>
        @endif
    </header>

    <main class="flex flex-1 flex-col">
        @yield('content')
    </main>
</body>

</html>