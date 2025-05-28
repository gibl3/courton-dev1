@extends('layouts.guest')

@section('title', 'Login - Courton')

@section('content')
<section class="flex-1 flex flex-row items-center justify-center mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
    <div class="flex flex-col flex-1 h-full items-center justify-center">
        <div class="flex flex-1 flex-col w-full sm:max-w-md gap-8 justify-center">
            <!-- Logo and Branding -->
            <div class="text-center space-y-2">
                <h1 class="text-2xl font-bold text-neutral-900">Welcome back</h1>
                <p class="text-sm text-neutral-600">Sign in to your account to continue</p>
            </div>

            <div class="rounded-xl bg-white shadow-lg border border-neutral-200">
                <div class="p-6 md:p-8">
                    <!-- Login Form -->
                    <form class="space-y-6" method="post" action="{{ route('auth.authenticate') }}" id="login-form">
                        @csrf

                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg hidden text-sm" id="errors-div"></div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-neutral-700">Email address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email"
                                    class="input-base"
                                    placeholder="Enter your email" />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-medium text-neutral-700">Password</label>
                                <a href="" class="text-xs text-rose-600/80 font-medium hover:text-rose-700 transition-colors">Forgot password?</a>
                            </div>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="input-base"
                                    placeholder="Enter your password" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-filled w-full">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sign Up Link -->
            <div class="text-center">
                <p class="text-sm text-neutral-600">
                    Don't have an account?
                    <a href="{{ route('auth.create') }}" class="text-rose-600 hover:text-rose-700 font-medium transition-colors">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@vite(['resources/js/auth/login.js'])
@endpush