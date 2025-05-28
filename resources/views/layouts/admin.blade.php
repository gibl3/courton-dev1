<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>@yield('title', 'Admin Dashboard - Courton')</title>
</head>

<body class="flex flex-col h-screen px-36 py-4 bg-neutral-50 text-neutral-900 font-normal">
    <header class="flex flex-row justify-between items-center">
        <div class="flex-1 flex flex-col">
            <a href="" class="text-xl font-semibold">Courton</a>
            <p class="text-xs">Admin Dashboard</p>
        </div>

        <nav class="flex-1 flex justify-center">
            <ul class="flex flex-row gap-x-6">
                <li><a href="" class="btn-base hover:text-neutral-900/70">Dashboard</a></li>
                <li><a href="" class="btn-base hover:text-neutral-900/70">Courts</a></li>
                <li><a href="" class="btn-base hover:text-neutral-900/70">Bookings</a></li>
                <li><a href="" class="btn-base hover:text-neutral-900/70">Users</a></li>
                <li><a href="" class="btn-base hover:text-neutral-900/70">Settings</a></li>
            </ul>
        </nav>

        <div class="flex-1 flex justify-end gap-x-2.5">
            <div class="relative group">
                <button class="btn-filled-tonal flex items-center gap-x-1">
                    <span class="material-symbols-rounded">
                        admin_panel_settings
                    </span>
                    {{ auth()->user()->name }}
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-neutral-200 hidden group-hover:block">
                    <div class="py-1">
                        <a href="" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Profile Settings</a>
                        <a href="" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">System Settings</a>
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