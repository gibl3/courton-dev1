@extends('layouts.guest')

@section('title', 'Sign Up - Courton')

@section('content')
<section class="flex-1 flex flex-row items-center py-12 justify-center mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
    <div popover id="success-popover" class="fixed ml-auto top-8 right-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg shadow-lg">
        <div class="flex items-center gap-2">
            <span class="material-symbols-rounded">check_circle</span>
            <p id="popover-message"></p>
        </div>
    </div>

    <div class="flex flex-col flex-1 h-full items-center justify-center">
        <div class="flex flex-1 flex-col w-full sm:max-w-md gap-8 justify-center">
            <!-- Logo and Branding -->
            <div class="text-center space-y-1">
                <h1 class="text-2xl font-bold text-neutral-900">Create an account</h1>
                <p class="text-sm text-neutral-600">Join us and start booking courts today</p>
            </div>

            <div class="rounded-xl bg-white shadow-lg border border-neutral-200">
                <div class="p-6 md:p-8">
                    <!-- Sign Up Form -->
                    <form class="space-y-4" method="post" action="{{ route('auth.store') }}" id="signup-form">
                        @csrf

                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg hidden text-sm" id="errors-div"></div>

                        <!-- First Name Field -->
                        <div class="space-y-1">
                            <label for="first-name" class="block text-sm font-medium text-neutral-700">
                                First name
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" id="first_name">
                                <input type="text" name="first_name"
                                    class="input-base"
                                    placeholder="Enter your first name" />
                            </div>
                        </div>

                        <!-- Last Name Field -->
                        <div class="space-y-1">
                            <label for="last-name" class="block text-sm font-medium text-neutral-700">
                                Last name
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" id="last_name">
                                <input type="text" name="last_name"
                                    class="input-base"
                                    placeholder="Enter your last name" />
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-1">
                            <label for="email-address" class="block text-sm font-medium text-neutral-700">
                                Email address
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" id="email">
                                <input type="email" name="email"
                                    class="input-base"
                                    placeholder="Enter your email" />
                            </div>
                        </div>

                        <!-- Phone Number Field -->
                        <div class="space-y-1">
                            <label for="phone-number" class="block text-sm font-medium text-neutral-700">Phone number</label>
                            <div class="relative" id="phone_number">
                                <input type="tel" name="phone"
                                    class="input-base"
                                    placeholder="Enter your phone number" />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-neutral-700">
                                Password
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" id="password">
                                <input type="password" name="password"
                                    class="input-base pr-12"
                                    placeholder="Create a password" />
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

                        <!-- Confirm Password Field -->
                        <div class="space-y-1">
                            <label for="password-confirmation" class="block text-sm font-medium text-neutral-700">
                                Confirm password
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative" id="password_confirmation">
                                <input type="password" name="password_confirmation"
                                    class="input-base pr-12"
                                    placeholder="Confirm your password" />
                                <button type="button" id="toggle-password-2"
                                    class="btn-base absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                                    <span class="material-symbols-rounded text-neutral-500">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div>
                            <div class="flex items-start" id="terms">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="terms"
                                        class="w-4 h-4 text-rose-600 border-neutral-300 rounded focus:ring-rose-500" />
                                </div>
                                <label for="terms" class="ml-2 block text-sm text-neutral-700">
                                    I agree to the
                                    <a href="#" class="text-rose-600/80 font-medium hover:text-rose-700 transition-colors">Terms of Service</a>
                                    and
                                    <a href="#" class="text-rose-600/80 font-medium hover:text-rose-700 transition-colors">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-filled w-full">
                            Create account
                        </button>
                    </form>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-neutral-600">
                    Already have an account?
                    <a href="{{ route('auth.login') }}" class="text-rose-600/80 font-medium hover:text-rose-700 transition-colors">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@vite(['resources/js/auth/create.js'])
@endpush