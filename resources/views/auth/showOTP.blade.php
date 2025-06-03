@extends('layouts.guest')

@section('title', 'Verify OTP')

@section('content')
<div class="flex items-center justify-center mx-auto flex-1">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-6">
            <span class="material-symbols-rounded md-icon-36 text-emerald-600 mb-2">lock</span>
            <h2 class="text-2xl font-semibold text-neutral-700">Verify Your Account</h2>
            <p class="text-sm text-neutral-600 mt-2">Please enter the 6-digit code sent to your email</p>
        </div>

        <form method="post" id="verify-otp-form" action="{{ route('auth.verifyOTP') }}" class="space-y-6">
            @csrf
            @method('post')

            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg hidden text-sm" id="errors-div">
            </div>

            <div class="space-y-4">
                <div class="flex justify-center gap-2">
                    @for ($i = 1; $i <= 6; $i++)
                        <input type="text"
                        maxlength="1"
                        pattern="[0-9]"
                        inputmode="numeric"
                        class="w-12 h-12 text-center text-lg font-semibold border border-neutral-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        data-index="{{ $i }}"
                        required>
                        @endfor
                </div>
                <p class="text-xs text-neutral-500 text-center">Enter the 6-digit code</p>
            </div>

            <div class="flex flex-col justify-center items-center space-y-4">
                <button type="submit" class="btn-filled" id="verify-submit">
                    <span class="material-symbols-rounded">verified_user</span>
                    Verify Code
                </button>

                <div class="text-center">
                    <p class="text-sm text-neutral-600 inline">Didn't receive the code?</p>
                    <button type="button" id="resend-code" class="btn-link text-sm font-medium inline">
                        Resend Code
                    </button>
                    <p class="text-xs text-neutral-500 mt-1" id="resend-timer"></p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/auth/verify-otp.js'])
@endpush