@extends('layouts.admin')

@section('title', 'Edit Court - Courton')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold">Edit {{ $court->name }}</h1>
            <p class="text-sm text-neutral-600">Update court information and settings</p>
        </div>
        <a href="{{ route('admin.courts.index') }}" class="btn-outline">
            <span class="material-symbols-rounded">arrow_back</span>
            Back to Courts
        </a>
    </div>

    <!-- Form Section -->
    <form action="{{ route('admin.courts.update', $court) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-neutral-200">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3">
            <!-- Left Column: Basic Information -->
            <div class="lg:col-span-2 p-6 border-b lg:border-b-0 lg:border-r border-neutral-200">
                <!-- Basic Information Section -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Court Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-neutral-700 mb-1.5">Court Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $court->name) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('name') border-red-500 @enderror"
                                placeholder="Enter court name">
                            @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Court Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-neutral-700 mb-1.5">Court Type</label>
                            <select name="type" id="type"
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('type') border-red-500 @enderror">
                                <option value="">Select type</option>
                                <option value="professional" {{ old('type', $court->type) == 'professional' ? 'selected' : '' }}>Professional</option>
                                <option value="standard" {{ old('type', $court->type) == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="training" {{ old('type', $court->type) == 'training' ? 'selected' : '' }}>Training</option>
                            </select>
                            @error('type')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-neutral-700 mb-1.5">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('description') border-red-500 @enderror"
                                placeholder="Enter court description">{{ old('description', $court->description) }}</textarea>
                            @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rates & Hours Section -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Rates & Hours</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Regular Rate -->
                        <div>
                            <label for="rate_per_hour" class="block text-sm font-medium text-neutral-700 mb-1.5">Regular Rate per Hour</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">₱</span>
                                <input type="number" name="rate_per_hour" id="rate_per_hour" value="{{ old('rate_per_hour', $court->rate_per_hour) }}" step="0.01" min="0"
                                    class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('rate_per_hour') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('rate_per_hour')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Weekend Rate -->
                        <div>
                            <label for="weekend_rate_per_hour" class="block text-sm font-medium text-neutral-700 mb-1.5">Weekend Rate per Hour</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">₱</span>
                                <input type="number" name="weekend_rate_per_hour" id="weekend_rate_per_hour" value="{{ old('weekend_rate_per_hour', $court->weekend_rate_per_hour) }}" step="0.01" min="0"
                                    class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('weekend_rate_per_hour') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('weekend_rate_per_hour')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Opening Time -->
                        <div>
                            <label for="opening_time" class="block text-sm font-medium text-neutral-700 mb-1.5">Opening Time</label>
                            <input type="time" name="opening_time" id="opening_time"
                                value="{{ old('opening_time', \Carbon\Carbon::parse($court->opening_time)->format('H:i')) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('opening_time') border-red-500 @enderror">
                            @error('opening_time')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Closing Time -->
                        <div>
                            <label for="closing_time" class="block text-sm font-medium text-neutral-700 mb-1.5">Closing Time</label>
                            <input type="time" name="closing_time" id="closing_time"
                                value="{{ old('closing_time', \Carbon\Carbon::parse($court->closing_time)->format('H:i')) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('closing_time') border-red-500 @enderror">
                            @error('closing_time')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Status</h2>
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 mb-1.5">Court Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('status') border-red-500 @enderror">
                            <option value="available" {{ old('status', $court->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status', $court->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            <option value="maintenance" {{ old('status', $court->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Image -->
            <div class="p-6 bg-neutral-50 lg:bg-white">
                <h2 class="text-lg font-semibold mb-4">Court Image</h2>
                <div>
                    <label for="image" class="block text-sm font-medium text-neutral-700 mb-1.5">Upload Image</label>
                    <div class="relative">
                        <input type="file" name="image" id="image" accept="image/*"
                            class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('image') border-red-500 @enderror"
                            onchange="previewImage(this)">
                        <p class="mt-1.5 text-xs text-neutral-500">Recommended size: 800x800px. Max file size: 2MB</p>
                    </div>
                    @error('image')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Image Preview -->
                    @if($court->image_path)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-neutral-700 mb-2">Preview</p>
                        <x-cloudinary::image
                            public-id="{{ $court->image_path }}"
                            alt="{{ $court->name }}"
                            class="w-full aspect-square object-cover rounded-lg border border-neutral-200"
                            width="800"
                            height="800"
                            crop="fill"
                            fetch-format="auto"
                            quality="auto" />
                    </div>
                    @endif
                </div>
            </div>
        </div>
</div>

<!-- Form Actions -->
<div class="p-6 bg-neutral-50 rounded-b-xl">
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.courts.index') }}" class="btn-text">Cancel</a>
        <button type="submit" class="btn-filled flex items-center gap-2">
            <span class="material-symbols-rounded">save</span>
            Update Court
        </button>
    </div>
</div>
</form>
</div>
@endsection