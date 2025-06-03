<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReCaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if value is empty
        if (empty($value)) {
            Log::warning('ReCaptcha validation failed: Empty value');
            $fail('The reCAPTCHA verification failed. Please complete the reCAPTCHA.');
            return;
        }

        // Check if secret key is configured
        $secretKey = config('services.recaptcha.secret_key');
        if (empty($secretKey)) {
            Log::error('ReCaptcha validation failed: Missing secret key configuration');
            $fail('The reCAPTCHA configuration is invalid. Please contact support.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip()
            ]);

            if (!$response->successful()) {
                Log::error('ReCaptcha API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $fail('The reCAPTCHA verification failed. Please try again.');
                return;
            }

            $responseData = $response->json();

            if (!isset($responseData['success'])) {
                Log::error('ReCaptcha API response missing success field', [
                    'response' => $responseData
                ]);
                $fail('The reCAPTCHA verification failed. Please try again.');
                return;
            }

            if (!$responseData['success']) {
                Log::warning('ReCaptcha verification failed', [
                    'error_codes' => $responseData['error-codes'] ?? [],
                    'ip' => request()->ip()
                ]);
                $fail('The reCAPTCHA verification failed. Please try again.');
                return;
            }
        } catch (\Exception $e) {
            Log::error('ReCaptcha validation exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
