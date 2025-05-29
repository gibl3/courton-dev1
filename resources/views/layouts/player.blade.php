<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @stack('scripts')
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>@yield('title', 'Player Dashboard - Courton')</title>
</head>

<body class="flex flex-col h-screen px-36 py-4 bg-neutral-50 text-neutral-900 font-normal">
    <header class="flex flex-row justify-between items-center">
        <div class="flex-1 flex flex-col">
            <a href="{{ route('player.dashboard') }}" class="text-xl font-semibold">Courton</a>
            <p class="text-xs">Player Dashboard</p>
        </div>

        <nav class="flex-1 flex justify-center">
            <ul class="flex flex-row gap-x-6">
                <li><a href="{{ route('player.dashboard') }}" class="btn-base hover:text-neutral-900/70">Dashboard</a></li>
                <li><a href="{{ route('player.book') }}" class="btn-base hover:text-neutral-900/70">Book a Court</a></li>
                <li><a href="{{ route('player.myBookings') }}" class="btn-base hover:text-neutral-900/70">My Bookings</a></li>
            </ul>
        </nav>

        <div class="flex-1 flex justify-end gap-x-2.5">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="btn-filled-tonal flex items-center gap-x-1">
                    <span class="material-symbols-rounded">
                        account_circle
                    </span>
                    Player
                </button>
                <div x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-neutral-200 z-50">
                    <div class="py-1">
                        <a href="{{ route('player.profile') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Profile Settings</a>
                        <a href="{{ route('player.myBookings') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">My Bookings</a>
                        <form method="POST" action="{{ route('auth.logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-neutral-100">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex flex-1 flex-col">
        @yield('content')
    </main>
</body>

</html>