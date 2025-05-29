@extends('layouts.admin')

@section('title', 'Add New Player - Courton')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Add New Player</h1>
            <p class="text-sm text-neutral-600">Create a new player account in the system</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-outline flex items-center gap-2">
            <span class="material-symbols-rounded">arrow_back</span>
            Back to Players
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
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-neutral-700 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('first_name') border-red-500 @enderror"
                            placeholder="Enter first name">
                        @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-neutral-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('last_name') border-red-500 @enderror"
                            placeholder="Enter last name">
                        @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-span-2">
                <h2 class="text-lg font-semibold mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('email') border-red-500 @enderror"
                            placeholder="Enter email address">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-neutral-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('phone') border-red-500 @enderror"
                            placeholder="Enter phone number">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Security -->
            <div class="col-span-2">
                <h2 class="text-lg font-semibold mb-4">Account Security</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-700 mb-1">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 @error('password') border-red-500 @enderror"
                            placeholder="Enter password">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500">Must be at least 8 characters long</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 rounded-lg border border-neutral-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500"
                            placeholder="Confirm password">
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="col-span-2">
                <div class="bg-neutral-50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-rounded text-neutral-400">info</span>
                        <div>
                            <h3 class="text-sm font-medium text-neutral-700">Important Notes</h3>
                            <ul class="mt-2 text-sm text-neutral-600 space-y-1 list-disc list-inside">
                                <li>The player's account will be automatically verified upon creation</li>
                                <li>Players can update their password after logging in</li>
                                <li>Email notifications will be sent to the provided email address</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-filled flex items-center gap-2">
                <span class="material-symbols-rounded">add</span>
                Create Player
            </button>
        </div>
    </form>
</div>
@endsection