@extends('layouts.admin')

@section('title', 'Courts Management - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Courts Management</h1>
            <p class="text-sm text-neutral-600">Manage and monitor all badminton courts</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courts.create') }}" class="btn-filled">
                <span class="material-symbols-rounded">add</span>
                Add New Court
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Court Types Overview -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Court Types</p>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            <span class="text-sm font-medium">Professional: {{ $stats['professional_courts'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-sm font-medium">Standard: {{ $stats['standard_courts'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-medium">Training: {{ $stats['training_courts'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability Status -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Availability</p>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-medium">Available: {{ $stats['available_courts'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span class="text-sm font-medium">Unavailable: {{ $stats['total_courts'] - $stats['available_courts'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operating Hours -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Operating Hours</p>
                    <div class="mt-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm text-neutral-400">schedule</span>
                            <span class="text-sm font-medium">06:00 AM - 10:00 PM</span>
                        </div>
                        <div class="mt-1 text-xs text-neutral-500">
                            Standard hours for all courts
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courts -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-600">Total Courts</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_courts']) }}</h3>
                    <div class="mt-1 text-xs text-neutral-500">
                        Active in the system
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <form action="{{ route('admin.courts.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courts..."
                        class="input-base pl-10">
                </div>
            </div>

            <!-- Type Filter -->
            <div class="w-full md:w-48">
                <select name="type" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    <option value="">All Types</option>
                    <option value="professional" {{ request('type') == 'professional' ? 'selected' : '' }}>Professional</option>
                    <option value="standard" {{ request('type') == 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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

    <!-- Courts Table -->
    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Court</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Rate/Hour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Weekend Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($courts as $court)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($court->image_path)
                                <div class="w-10 h-10 rounded-lg overflow-hidden">
                                    <x-cloudinary::image
                                        public-id="{{ $court->image_path }}"
                                        alt="{{ $court->name }}"
                                        class="w-full h-full object-cover"
                                        width="40"
                                        height="40"
                                        crop="fill"
                                        fetch-format="auto"
                                        quality="auto" />
                                </div>
                                @else
                                <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-neutral-600">sports_tennis</span>
                                </div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $court->name }}</div>
                                    <div class="text-sm text-neutral-600">{{ Str::limit($court->description, 30) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $court->type === 'professional' ? 'bg-purple-100 text-purple-800' : 
                                   ($court->type === 'standard' ? 'bg-blue-100 text-blue-800' : 
                                    'bg-emerald-100 text-emerald-800') }}">
                                {{ ucfirst($court->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $court->status === 'available' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($court->status === 'unavailable' ? 'bg-red-100 text-red-800' : 
                                    'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($court->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            ${{ number_format($court->rate_per_hour, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            ${{ number_format($court->weekend_rate_per_hour, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            {{ \Carbon\Carbon::parse($court->opening_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($court->closing_time)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.courts.edit', $court) }}" class="p-2 text-neutral-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <span class="material-symbols-rounded">edit</span>
                                </a>
                                <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete {{ $court->name }}? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-neutral-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <span class="material-symbols-rounded">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-neutral-600">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl">sports_tennis</span>
                                <p>No courts found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $courts->links() }}
        </div>
    </div>
</div>
@endsection