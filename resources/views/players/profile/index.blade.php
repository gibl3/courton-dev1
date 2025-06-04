@extends('layouts.player')

@section('title', 'My Profile - Courton')

@section('content')
<div class="flex flex-col gap-8 py-12" x-data="{ 
    firstName: '{{ $user->first_name }}',
    lastName: '{{ $user->last_name }}',
    get fullName() {
        return `${this.firstName} ${this.lastName}`.trim();
    }
}">
    <!-- Page Header -->
    <section class="bg-white rounded-xl border border-neutral-200 p-8">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                    <img
                        src="{{ Auth::user()->avatar ?? 'https://api.dicebear.com/9.x/bottts/svg?seed=' . Auth::user()->getFullNameAttribute() .
                        '&backgroundColor=c70036'}}"
                        alt="{{ Auth::user()->getFullNameAttribute() }}"
                        class="size-full rounded-full object-cover border border-neutral-200">
                </div>
                <div>
                    <h1 class="text-2xl font-bold" x-text="fullName">{{ $user->fullname }}</h1>
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
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold mb-6">Personal Information</h2>
                <form id="profile-form" action="{{ route('player.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('POST')

                    <div class="px-4 py-3 rounded-lg hidden text-sm" id="profile-response" hidden>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">First Name</label>
                            <input type="text" name="first_name" x-model="firstName" value="{{ $user->first_name }}" class="input-base">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">Last Name</label>
                            <input type="text" name="last_name" x-model="lastName" value="{{ $user->last_name }}" class="input-base">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">Email</label>
                            <input type="email" value="{{ $user->email }}" class="input-base bg-gray-100" disabled>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-neutral-700">Phone</label>
                            <input type="tel" name="phone" value="{{ $user->phone }}" class="input-base">
                        </div>
                    </div>
                    <button type="submit" class="btn-filled">Save Changes</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold mb-6">Change Password</h2>
                <form id="password-form" action="{{ route('player.profile.change-password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('POST')

                    <div class="px-4 py-3 rounded-lg hidden text-sm" id="password-response" hidden>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">Current Password</label>
                        <input type="password" name="current_password" class="input-base">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" class="input-base">
                            <button type="button" id="toggle-password-1"
                                class="btn-base absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                                <span class="material-symbols-rounded text-neutral-500">visibility</span>
                            </button>
                        </div>

                        <div class="text-sm text-neutral-600">
                            <p class="mb-1">Password must contain:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li id="length-check" class="text-red-500">At least 8 characters</li>
                                <li id="uppercase-check" class="text-red-500">One uppercase letter</li>
                                <li id="lowercase-check" class="text-red-500">One lowercase letter</li>
                                <li id="number-check" class="text-red-500">One number</li>
                                <li id="special-check" class="text-red-500">One special character</li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-neutral-700">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" class="input-base">
                            <button type="button" id="toggle-password-2"
                                class="btn-base absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                                <span class="material-symbols-rounded text-neutral-500">visibility</span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-filled">Update Password</button>
                </form>
            </div>
        </section>

        <!-- Account Summary -->
        <section class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-neutral-200 p-6 sticky top-8">
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
                        <button type="button" onclick="deleteDialog.showModal()" class="btn-text text-red-600 w-full">Delete Account</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Delete Account Dialog -->
<dialog id="deleteDialog" class="p-0 rounded-xl m-auto border border-neutral-200 shadow-lg backdrop:bg-black/50">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Delete Account</h3>
        <p class="text-neutral-600 mb-6">This action cannot be undone. Please enter your password to confirm.</p>

        <form id="delete-form" action="{{ route('player.profile.deleteUser') }}" method="POST" class="space-y-4">
            @csrf
            @method('post')

            <div class="px-4 py-3 rounded-lg hidden text-sm" id="delete-response" hidden>
            </div>

            <label class="block text-sm font-medium text-neutral-700">Confirm Password</label>
            <div class="relative">
                <input type="password" name="password" id="confirm-delete-password" class="input-base w-full" required>
                <button type="button" id="toggle-password-3"
                    class="btn-base absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                    <span class="material-symbols-rounded text-neutral-500">visibility</span>
                </button>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="deleteDialog.close()" class="btn-text">Cancel</button>
                <button type="submit" class="btn-filled bg-red-600 hover:bg-red-700">Delete Account</button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
@vite(['resources/js/profile/profile.js'])
@endpush