<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = config('mail.mailers.smtp.password');
$fromEmail = config('mail.from.address');
$fromName = config('mail.from.name');
$toEmail = "faustin.ndayishimiye40@gmail.com";

$email = new \SendGrid\Mail\Mail();
$email->setFrom($fromEmail, $fromName);
$email->setSubject("Test Email from eVuba via SendGrid SDK");
$email->addTo($toEmail, "Test User");
$email->addContent("text/html", "<strong>If you see this, the SendGrid SDK Integration is working perfectly!</strong>");

$sendgrid = new \SendGrid($apiKey);

// Set EU residency as requested by the user
if (method_exists($sendgrid, 'set_sendgrid_data_residency')) {
    $sendgrid->set_sendgrid_data_residency("eu");
    echo "EU Data Residency set successfully.\n";
}

try {
    $response = $sendgrid->send($email);
    echo "Status Code: " . $response->statusCode() . "\n";
    echo "Body: " . $response->body() . "\n";
    echo "Headers: \n";
    print_r($response->headers());
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
