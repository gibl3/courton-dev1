@extends('layouts.admin')

@section('title', 'Create New Court - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Create New Court</h1>
            <p class="text-sm text-neutral-600">Add a new badminton court to the system</p>
        </div>
        <a href="" class="btn-outline flex items-center gap-2">
            <span class="material-symbols-rounded">arrow_back</span>
            Back to Courts
        </a>
    </div>

    <!-- Form Section -->
    <form action="" method="POST" class="bg-white rounded-xl border border-neutral-200 p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="col-span-2">
                <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Court Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-neutral-700 mb-1">Court Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('name') border-red-500 @enderror"
                            placeholder="Enter court name">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Court Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-neutral-700 mb-1">Court Type</label>
                        <select name="type" id="type"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('type') border-red-500 @enderror">
                            <option value="">Select type</option>
                            <option value="professional" {{ old('type') == 'professional' ? 'selected' : '' }}>Professional</option>
                            <option value="standard" {{ old('type') == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="training" {{ old('type') == 'training' ? 'selected' : '' }}>Training</option>
                        </select>
                        @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="col-span-2">
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('description') border-red-500 @enderror"
                    placeholder="Enter court description">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rates Section -->
            <div class="col-span-2">
                <h2 class="text-lg font-semibold mb-4">Rates & Hours</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Regular Rate -->
                    <div>
                        <label for="rate_per_hour" class="block text-sm font-medium text-neutral-700 mb-1">Regular Rate per Hour ($)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">$</span>
                            <input type="number" name="rate_per_hour" id="rate_per_hour" value="{{ old('rate_per_hour') }}" step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('rate_per_hour') border-red-500 @enderror"
                                placeholder="0.00">
                        </div>
                        @error('rate_per_hour')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weekend Rate -->
                    <div>
                        <label for="weekend_rate_per_hour" class="block text-sm font-medium text-neutral-700 mb-1">Weekend Rate per Hour ($)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">$</span>
                            <input type="number" name="weekend_rate_per_hour" id="weekend_rate_per_hour" value="{{ old('weekend_rate_per_hour') }}" step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('weekend_rate_per_hour') border-red-500 @enderror"
                                placeholder="0.00">
                        </div>
                        @error('weekend_rate_per_hour')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opening Time -->
                    <div>
                        <label for="opening_time" class="block text-sm font-medium text-neutral-700 mb-1">Opening Time</label>
                        <input type="time" name="opening_time" id="opening_time" value="{{ old('opening_time') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('opening_time') border-red-500 @enderror">
                        @error('opening_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Closing Time -->
                    <div>
                        <label for="closing_time" class="block text-sm font-medium text-neutral-700 mb-1">Closing Time</label>
                        <input type="time" name="closing_time" id="closing_time" value="{{ old('closing_time') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('closing_time') border-red-500 @enderror">
                        @error('closing_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="col-span-2">
                <h2 class="text-lg font-semibold mb-4">Status</h2>
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Court Status</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('status') border-red-500 @enderror">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex items-center justify-end gap-3">
            <a href="{{ route('admin.courts.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-filled flex items-center gap-2">
                <span class="material-symbols-rounded">add</span>
                Create Court
            </button>
        </div>
    </form>
</div>
@endsection