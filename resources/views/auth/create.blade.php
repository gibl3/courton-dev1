@extends('layouts.guest')

@section('title', 'Sign Up - Courton')

@section('content')
<section class="flex-1 flex flex-row items-center py-12 justify-center mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
    <div class="flex flex-col flex-1 h-full items-center justify-center">
        <div class="flex flex-1 flex-col w-full sm:max-w-md gap-8 justify-center">
            <!-- Logo and Branding -->
            <div class="text-center space-y-2">
                <h1 class="text-2xl font-bold text-neutral-900">Create an account</h1>
                <p class="text-sm text-neutral-600">Join us and start booking courts today</p>
            </div>

            <div class="rounded-xl bg-white shadow-lg border border-neutral-200">
                <div class="p-6 md:p-8">
                    <!-- Sign Up Form -->
                    <form class="space-y-6" method="post" action="{{ route('auth.store') }}" id="signup-form">
                        @csrf

                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg hidden text-sm" id="errors-div"></div>

                        <!-- First Name Field -->
                        <div class="space-y-2">
                            <label for="first-name" class="block text-sm font-medium text-neutral-700">First name</label>
                            <div class="relative">
                                <input type="text" name="first_name" id="first-name"
                                    class="input-base"
                                    placeholder="Enter your first name" />
                            </div>
                        </div>

                        <!-- Last Name Field -->
                        <div class="space-y-2">
                            <label for="last-name" class="block text-sm font-medium text-neutral-700">Last name</label>
                            <div class="relative">
                                <input type="text" name="last_name" id="last-name"
                                    class="input-base"
                                    placeholder="Enter your last name" />
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="email-address" class="block text-sm font-medium text-neutral-700">Email address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email-address"
                                    class="input-base"
                                    placeholder="Enter your email" />
                            </div>
                        </div>

                        <!-- Phone Number Field -->
                        <div class="space-y-2">
                            <label for="phone-number" class="block text-sm font-medium text-neutral-700">Phone number</label>
                            <div class="relative">
                                <input type="tel" name="phone" id="phone-number"
                                    class="input-base"
                                    placeholder="Enter your phone number" />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-neutral-700">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="input-base"
                                    placeholder="Create a password" />
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label for="password-confirmation" class="block text-sm font-medium text-neutral-700">Confirm password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password-confirmation"
                                    class="input-base"
                                    placeholder="Confirm your password" />
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="terms" name="terms"
                                    class="w-4 h-4 text-rose-600 border-neutral-300 rounded focus:ring-rose-500" />
                            </div>
                            <label for="terms" class="ml-2 block text-sm text-neutral-700">
                                I agree to the
                                <a href="#" class="text-rose-600/80 font-medium hover:text-rose-700 transition-colors">Terms of Service</a>
                                and
                                <a href="#" class="text-rose-600/80 font-medium hover:text-rose-700 transition-colors">Privacy Policy</a>
                            </label>
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