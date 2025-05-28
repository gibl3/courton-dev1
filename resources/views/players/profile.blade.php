@extends('layouts.player')

@section('title', 'My Profile - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12">
    <!-- Page Header -->
    <section class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-3xl text-rose-600">
                        person
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">My Profile</h1>
                    <p class="text-neutral-600">Manage your account settings</p>
                </div>
            </div>
        </div>
    </section>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information -->
        <section class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold mb-6">Personal Information</h2>
                <form action="{{ route('player.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">First Name</label>
                            <input type="text" name="first_name" value="{{ $user->first_name }}" class="input-base @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">Last Name</label>
                            <input type="text" name="last_name" value="{{ $user->last_name }}" class="input-base @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">Email</label>
                            <input type="email" value="{{ $user->email }}" class="input-base bg-gray-100" disabled>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">Phone</label>
                            <input type="tel" name="phone" value="{{ $user->phone }}" class="input-base @error('phone') border-red-500 @enderror">
                            @error('phone')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn-filled">Save Changes</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold mb-6">Change Password</h2>
                <form action="{{ route('player.profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">Current Password</label>
                        <input type="password" name="current_password" class="input-base @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">New Password</label>
                        <input type="password" name="password" class="input-base @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="input-base">
                    </div>
                    <button type="submit" class="btn-filled">Update Password</button>
                </form>
            </div>
        </section>

        <!-- Account Summary -->
        <section class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
                <h2 class="text-lg font-semibold mb-6">Account Summary</h2>
                <div class="space-y-6">
                    <!-- Member Since -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Member Since</label>
                        <p class="font-medium">{{ $user->created_at->format('F Y') }}</p>
                    </div>

                    <!-- Total Bookings -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Total Bookings</label>
                        <p class="font-medium">{{ $user->bookings->count() }}</p>
                    </div>

                    <!-- Account Type -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-neutral-600">Account Type</label>
                        <p class="font-medium">{{ ucfirst($user->role) }}</p>
                    </div>

                    <!-- Account Actions -->
                    <div class="pt-6 border-t border-neutral-200">
                        <button class="btn-base text-red-600 w-full">Delete Account</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection