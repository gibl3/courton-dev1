@extends('layouts.admin')

@section('title', 'Players Management - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Session Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4" role="alert">
        <div class="flex items-center gap-3">
            <span class="material-symbols-rounded text-emerald-500">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4" role="alert">
        <div class="flex items-center gap-3">
            <span class="material-symbols-rounded text-rose-500">error</span>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Players Management</h1>
            <p class="text-sm text-neutral-600">Manage and monitor all registered players</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="btn-filled flex items-center gap-2">
                <span class="material-symbols-rounded">add</span>
                Add New Player
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Players -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Total Players</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_users']) }}</h3>
                    <div class="mt-1 text-xs text-neutral-500">
                        Registered accounts
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Status -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Verification Status</p>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-medium">Verified: {{ $stats['verified_count'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-sm font-medium">Unverified: {{ $stats['unverified_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Players -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">New Players</p>
                    <div class="mt-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm text-neutral-400">group</span>
                            <span class="text-sm font-medium">{{ $stats['new_users_today'] }} today</span>
                        </div>
                        <div class="mt-1 text-xs text-neutral-500">
                            Last 24 hours
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Quick Actions</p>
                    <div class="mt-2 space-y-2">
                        <button class="w-full text-left px-3 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-50 rounded-lg transition-colors">
                            Export Players List
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-50 rounded-lg transition-colors">
                            Send Bulk Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search players..."
                        class="input-base pl-10">
                </div>
            </div>

            <!-- Verification Filter -->
            <div class="w-full md:w-48">
                <select name="verified" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    <option value="">All Players</option>
                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>

            <!-- Search Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="btn-filled-tonal">
                    <span class="material-symbols-rounded">search</span>
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Players Table -->
    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Player</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Bookings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-neutral-600">person</span>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $user->full_name }}</div>
                                    <div class="text-sm text-neutral-600">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->email_verified_at ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            {{ $user->bookings_count }} bookings
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-neutral-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <span class="material-symbols-rounded">edit</span>
                                </a>
                                <button class="p-2 text-neutral-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <span class="material-symbols-rounded">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-neutral-600">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl">group</span>
                                <p>No players found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection