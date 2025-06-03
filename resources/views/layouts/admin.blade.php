<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @stack('scripts')
    @stack('styles')
    <title>@yield('title', 'Admin Dashboard - Courton')</title>
</head>

<body class="flex flex-col h-screen bg-neutral-50 text-neutral-900 font-normal" x-data="{ 
    user: {
        name: '{{ Auth::check() ? (Auth::user()->first_name ?? Auth::user()->name ?? 'Admin') : 'Guest' }}',
        isAdmin: {{ Auth::check() && Auth::user()->role === 'admin' ? 'true' : 'false' }}
    }
}">
    <!-- Top Header -->
    <header class="h-16 border-b border-neutral-200 bg-white">
        <div class="h-full px-8 flex items-center justify-between">
            <div class="flex flex-col">
                <a href="{{ route('admin.index') }}" class="text-xl font-semibold">Courton</a>
                <p class="text-xs">Admin Dashboard</p>
            </div>

            <div class="flex items-center gap-x-2.5">
                <div class="relative" x-data="{ isOpen: false }" @click.away="isOpen = false">
                    <button @click="isOpen = !isOpen" class="btn-filled-tonal">
                        <span class="material-symbols-rounded">
                            admin_panel_settings
                        </span>
                        <span x-text="user.name"></span>
                    </button>
                    <div x-show="isOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-neutral-200 z-50" x-cloak>

                        <div class="py-2">
                            <!-- Profile Settings -->
                            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                <span class="material-symbols-rounded text-lg">person</span>
                                Profile Settings
                            </a>

                            <!-- System Settings -->
                            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">
                                <span class="material-symbols-rounded text-lg">settings</span>
                                System Settings
                            </a>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('auth.logout') }}" class="block">
                                @csrf
                                @method('post')
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-neutral-100">
                                    <span class="material-symbols-rounded text-lg">logout</span>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-neutral-200">
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.index') ? 'bg-rose-50 text-rose-600' : '' }}">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <!-- Courts Dropdown -->
                <div x-data="dropdown('courts')" class="relative">
                    <button @click="toggle" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.courts*') ? 'bg-rose-50 text-rose-600' : '' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded">sports_tennis</span>
                            <span>Courts</span>
                        </div>
                        <span class="material-symbols-rounded text-lg transition-transform" :class="{ 'rotate-180': isOpen }">expand_more</span>
                    </button>
                    <div x-show="isOpen" x-cloak class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('admin.courts.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.courts.index') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">list</span>
                            <span>All Courts</span>
                        </a>
                        <a href="{{ route('admin.courts.create') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.courts.create') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">add</span>
                            <span>Add Court</span>
                        </a>
                    </div>
                </div>

                <!-- Bookings Dropdown -->
                <div x-data="dropdown('bookings')" class="relative">
                    <button @click="toggle" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings*') ? 'bg-rose-50 text-rose-600' : '' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded">calendar_month</span>
                            <span>Bookings</span>
                        </div>
                        <span class="material-symbols-rounded text-lg transition-transform" :class="{ 'rotate-180': isOpen }">expand_more</span>
                    </button>
                    <div x-show="isOpen" x-cloak class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings.index') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">list</span>
                            <span>All Bookings</span>
                        </a>
                        <a href="{{ route('admin.bookings.create') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings.create') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">add</span>
                            <span>Create Booking</span>
                        </a>
                        <a href="{{ route('admin.bookings.pending') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings.pending') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">pending</span>
                            <span>Booking Status</span>
                        </a>
                        <a href="{{ route('admin.bookings.pendingPayments') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings.pendingPayments') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">payments</span>
                            <span>Payment Status</span>
                        </a>
                    </div>
                </div>

                <!-- Users Dropdown -->
                <div x-data="dropdown('users')" class="relative">
                    <button @click="toggle" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.users*') ? 'bg-rose-50 text-rose-600' : '' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded">group</span>
                            <span>Players</span>
                        </div>
                        <span class="material-symbols-rounded text-lg transition-transform" :class="{ 'rotate-180': isOpen }">expand_more</span>
                    </button>
                    <div x-show="isOpen" x-cloak class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.users.index') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">list</span>
                            <span>All Players</span>
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.users.create') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">person_add</span>
                            <span>Add Player</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-rose-50 text-rose-600' : '' }}">
                    <span class="material-symbols-rounded">settings</span>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', (section) => ({
                isOpen: false,
                init() {
                    this.isOpen = window.location.pathname.includes(`/admin/${section}`)
                },
                toggle() {
                    this.isOpen = !this.isOpen
                },
                close() {
                    this.isOpen = false
                }
            }))
        })
    </script>
</body>

</html>