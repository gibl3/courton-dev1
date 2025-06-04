@extends('layouts.guest')

@section('title', 'Forgot Password - Courton')

@section('content')
<section class="flex-1 flex flex-row items-center justify-center mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
    <div class="flex flex-col flex-1 h-full items-center justify-center">
        <div class="flex flex-1 flex-col w-full sm:max-w-md gap-8 justify-center">
            <!-- Logo and Branding -->
            <div class="text-center space-y-2">
                <h1 class="text-2xl font-bold text-neutral-900">Forgot Password</h1>
                <p class="text-sm text-neutral-600">Enter your email and verification code to reset your password.</p>
            </div>

            <div class="rounded-xl bg-white shadow-lg border border-neutral-200">
                <div class="p-6 md:p-8">
                    <!-- Forgot Password Form -->
                    <form id="forgot-password-form" class="space-y-6">
                        @csrf
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg hidden text-sm" id="errors-div"></div>

                        <!-- Email Field with Send Button -->
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-medium text-neutral-700">Email address</label>
                            <div class="relative flex gap-2">
                                <input type="email" id="email" name="email" required
                                    class="input-base flex-1"
                                    placeholder="Enter your email">
                                <button type="button" id="send-otp-btn"
                                    class="btn-filled-tonal p-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    <span class="material-symbols-rounded">send</span>
                                </button>
                            </div>
                        </div>

                        <!-- OTP Field -->
                        <div class="space-y-1">
                            <label for="otp" class="block text-sm font-medium text-neutral-700">Verification Code</label>
                            <div class="relative">
                                <input type="text" id="otp" name="otp" required
                                    class="input-base"
                                    placeholder="Enter verification code"
                                    maxlength="6"
                                    pattern="[0-9]{6}">
                            </div>
                        </div>

                        <!-- New Password Field -->
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-neutral-700">New Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                    class="input-base pr-12"
                                    placeholder="Enter new password">
                                <button type="button" id="toggle-password"
                                    class="btn-base absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                                    <span class="material-symbols-rounded text-neutral-500">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-sm font-medium text-neutral-700">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="input-base pr-12"
                                    placeholder="Confirm new password">
                                <button type="button" id="toggle-password-confirmation"
                                    class="btn-base absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0">
                                    <span class="material-symbols-rounded text-neutral-500">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Reset Password Button -->
                        <button type="submit" id="reset-password-btn" class="btn-filled w-full flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded">lock_reset</span>
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sign Up Link -->
            <div class="text-center">
                <a href="{{ route('auth.login') }}" class="text-xs text-rose-600/80 font-medium hover:text-rose-700 transition-colors">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgot-password-form');
        const sendOtpBtn = document.getElementById('send-otp-btn');
        const resetPasswordBtn = document.getElementById('reset-password-btn');
        const emailInput = document.getElementById('email');
        const otpInput = document.getElementById('otp');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('toggle-password');
        const togglePasswordConfirmation = document.getElementById('toggle-password-confirmation');

        // Initially disable OTP input, password fields, and reset button
        otpInput.disabled = true;
        passwordInput.disabled = true;
        passwordConfirmationInput.disabled = true;
        resetPasswordBtn.disabled = true;

        // Enable/disable send OTP button based on email input
        emailInput.addEventListener('input', function() {
            sendOtpBtn.disabled = !this.value || !this.checkValidity();
        });

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.querySelector('span').textContent = type === 'password' ? 'visibility' : 'visibility_off';
        });

        // Toggle password confirmation visibility
        togglePasswordConfirmation.addEventListener('click', function() {
            const type = passwordConfirmationInput.type === 'password' ? 'text' : 'password';
            passwordConfirmationInput.type = type;
            this.querySelector('span').textContent = type === 'password' ? 'visibility' : 'visibility_off';
        });

        // Send OTP
        sendOtpBtn.addEventListener('click', async function() {
            try {
                // Disable button and show loading state
                sendOtpBtn.disabled = true;
                sendOtpBtn.innerHTML = `
                    <span class="material-symbols-rounded animate-spin">sync</span>
                    Sending...
                `;

                const response = await fetch('{{ route("auth.forgot-password.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: emailInput.value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw data.errors || 'Failed to send verification code';
                }

                // Show success message
                alert(data.message);

                // Enable OTP input and password fields
                otpInput.disabled = false;
                passwordInput.disabled = false;
                passwordConfirmationInput.disabled = false;
                resetPasswordBtn.disabled = false;
            } catch (error) {
                alert(error.email || 'An error occurred. Please try again.');
            } finally {
                // Reset button state
                sendOtpBtn.disabled = !emailInput.value || !emailInput.checkValidity();
                sendOtpBtn.innerHTML = `
                    <span class="material-symbols-rounded">send</span>
                `;
            }
        });

        // Reset Password
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                // Disable button and show loading state
                resetPasswordBtn.disabled = true;
                resetPasswordBtn.innerHTML = `
                    <span class="material-symbols-rounded animate-spin">sync</span>
                    Resetting...
                `;

                const response = await fetch('{{ route("auth.forgot-password.reset") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: emailInput.value,
                        otp: otpInput.value,
                        password: passwordInput.value,
                        password_confirmation: passwordConfirmationInput.value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to reset password');
                }

                // Show success message
                alert(data.message);

                // Redirect to login page
                window.location.href = data.redirect;
            } catch (error) {
                alert(error.errors || 'An error occurred. Please try again.');
            } finally {
                // Reset button state
                resetPasswordBtn.disabled = false;
                resetPasswordBtn.innerHTML = `
                    <span class="material-symbols-rounded">lock_reset</span>
                    Reset Password
                `;
            }
        });
    });
</script>
@endpush