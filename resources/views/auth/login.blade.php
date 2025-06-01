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
                                    class="input-base pr-12"
                                    placeholder="Enter your password" />
                                <button type="button" id="toggle-password"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                                    <span class="material-symbols-rounded text-neutral-500">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="g-recaptcha w-full" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-filled w-full">
                            Sign in
                        </button>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-neutral-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-neutral-500">Or continue with</span>
                            </div>
                        </div>

                        <!-- Google Login Button -->
                        <a href="{{ route('auth.google') }}" class="btn-outlined w-full flex items-center justify-center gap-2">
                            <svg class="size-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                            </svg>
                            Sign in with Google
                        </a>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush