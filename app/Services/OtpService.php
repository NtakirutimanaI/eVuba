<?php

namespace App\Services;

use SendGrid\Mail\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;

    public function __construct()
    {
        $this->apiKey = config('mail.mailers.smtp.password');
        $this->fromEmail = config('mail.from.address');
        $this->fromName = config('mail.from.name');
    }

    /**
     * Generate and send OTP to user
     */
    public function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        // Store OTP in cache associated with user email
        Cache::put('otp_' . $user->email, $otp, $expiresAt);

        try {
            $email = new Mail();
            $email->setFrom($this->fromEmail, $this->fromName);
            $email->setSubject("Your Verification Code - " . $this->fromName);
            $email->addTo($user->email, $user->name);

            $htmlContent = view('emails.otp', ['otp' => $otp, 'name' => $user->name])->render();
            $email->addContent("text/html", $htmlContent);

            $sendgrid = new \SendGrid($this->apiKey);

            Log::info('Attempting to send OTP via SendGrid', [
                'user' => $user->email,
                'from' => $this->fromEmail,
                'residency' => env('SENDGRID_DATA_RESIDENCY', 'global')
            ]);

            // Set Data Residency based on configuration
            $residency = env('SENDGRID_DATA_RESIDENCY', 'global');

            if ($residency === 'eu') {
                if (method_exists($sendgrid, 'set_sendgrid_data_residency')) {
                    $sendgrid->set_sendgrid_data_residency("eu");
                } else {
                    $sendgrid->client->setHost("https://api.eu.sendgrid.com");
                }
            } else {
                // Ensure global host is used
                $sendgrid->client->setHost("https://api.sendgrid.com");
            }

            $response = $sendgrid->send($email);

            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info('SendGrid OTP Accepted', [
                    'status' => $response->statusCode(),
                    'message_id' => $response->headers()[4] ?? 'unknown'
                ]);
                return true;
            }

            Log::error('SendGrid OTP Delivery Failed', [
                'status' => $response->statusCode(),
                'body' => $response->body(),
                'user' => $user->email
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('SendGrid OTP Exception', [
                'message' => $e->getMessage(),
                'user' => $user->email
            ]);
            return false;
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($email, $code)
    {
        $cachedOtp = Cache::get('otp_' . $email);

        if ($cachedOtp && $cachedOtp == $code) {
            Cache::forget('otp_' . $email);
            return true;
        }

        return false;
    }
}
