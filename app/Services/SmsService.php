<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendSmsOtp($otp)
    {
        // Sanitize inputs
        $phoneNumber = preg_replace('/\D/', '', $otp->mobile);
        
        // Only add country code if it's a 10-digit number
        if (strlen($phoneNumber) === 10) {
            $countryCode = preg_replace('/\D/', '', $otp->phone_country_code ?? '91');
            $formattedPhone = $countryCode . $phoneNumber;
        } else {
            $formattedPhone = $phoneNumber;
        }

        // Extract actual OTP value
        $otpValue = $otp->otp;

        // Build URL safely
        $smsApiUrl = env('SMS_API_URL', 'http://api.simpel.ai/api/send-otp');
        $url = sprintf(
            '%s/%s/%s',
            $smsApiUrl,
            $formattedPhone,
            $otpValue
        );

        try {
            $response = Http::timeout(5)
                ->retry(2, 100)
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            // Log API failure
            Log::error('SMS API failed', [
                'url' => $url,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('SMS sending exception', [
                'message' => $e->getMessage(),
                'url' => $url,
            ]);

            return false;
        }
    }
}

