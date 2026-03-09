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
            $htmlContent = view('emails.otp', ['otp' => $otp, 'name' => $user->name])->render();

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($this->fromEmail, $this->fromName);
            $email->setSubject("Your Verification Code - " . $this->fromName);
            $email->addTo($user->email, $user->name);
            $email->addContent("text/html", $htmlContent);

            $sendgrid = new \SendGrid($this->apiKey);

            $response = $sendgrid->send($email);

            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info('SendGrid OTP Accepted successfully', [
                    'user' => $user->email,
                    'status' => $response->statusCode()
                ]);
                return true;
            } else {
                Log::error('SendGrid OTP Failed', [
                    'user' => $user->email,
                    'status' => $response->statusCode(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('OTP Mailer Exception', [
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
