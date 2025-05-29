<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    <title>@yield('title', 'Admin Dashboard - Courton')</title>
</head>

<body class="flex flex-col h-screen bg-neutral-50 text-neutral-900 font-normal">
    <!-- Top Header -->
    <header class="h-16 border-b border-neutral-200 bg-white">
        <div class="h-full px-8 flex items-center justify-between">
            <div class="flex-1 flex flex-col">
                <a href="{{ route('admin.index') }}" class="text-xl font-semibold">Courton</a>
                <p class="text-xs">Admin Dashboard</p>
            </div>

            <div class="flex items-center gap-x-2.5">
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
                    <div x-show="isOpen" x-cloak @click.away="close" class="mt-1 ml-4 space-y-1">
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
                    <div x-show="isOpen" x-cloak @click.away="close" class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">list</span>
                            <span>All Bookings</span>
                        </a>
                        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.bookings.pending') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">pending</span>
                            <span>Pending</span>
                        </a>
                    </div>
                </div>

                <!-- Users Dropdown -->
                <div x-data="dropdown('users')" class="relative">
                    <button @click="toggle" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.users*') ? 'bg-rose-50 text-rose-600' : '' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded">group</span>
                            <span>Users</span>
                        </div>
                        <span class="material-symbols-rounded text-lg transition-transform" :class="{ 'rotate-180': isOpen }">expand_more</span>
                    </button>
                    <div x-show="isOpen" x-cloak @click.away="close" class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.users') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">list</span>
                            <span>All Users</span>
                        </a>
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-neutral-700 hover:bg-neutral-100 transition-colors {{ request()->routeIs('admin.users.create') ? 'bg-rose-50 text-rose-600' : '' }}">
                            <span class="material-symbols-rounded text-lg">person_add</span>
                            <span>Add User</span>
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