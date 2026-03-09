<?php
$user = new \App\Models\User();
$user->email = 'faustin.ndayishimiye40@gmail.com';
$user->name = 'Test User';

$service = app(\App\Services\OtpService::class);
$result = $service->sendOtp($user);

file_put_contents('otp_result.txt', $result ? "OTP Sent Successfully\n" : "OTP Failed to Send\n");
echo "Done.";
