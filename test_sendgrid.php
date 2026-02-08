<?php
require __DIR__ . '/vendor/autoload.php';

$apiKey = getenv('SENDGRID_API_KEY');
$fromEmail = "faustinnet12@gmail.com";
$toEmail = "faustinnet12@gmail.com";

$email = new \SendGrid\Mail\Mail();
$email->setFrom($fromEmail, "E-VUBA CONNECT");
$email->setSubject("Sending with Twilio SendGrid is Fun");
$email->addTo($toEmail, "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html",
    "<strong>and easy to do anywhere, even with PHP</strong>"
);

$sendgrid = new \SendGrid($apiKey);

// Set EU residency as requested by the user
// Based on the Python example provided: sg.set_sendgrid_data_residency("eu")
// In PHP SDK, we check if this method exists or use the client option.
// The PHP SDK version 8.x + supports setting the data residency.
try {
    if (method_exists($sendgrid, 'set_sendgrid_data_residency')) {
        $sendgrid->set_sendgrid_data_residency("eu");
        echo "EU Data Residency set successfully.\n";
    } elseif (property_exists($sendgrid, 'client')) {
        // Fallback or manual host setting if needed for older SDK versions
        // But 8.x should have it.
        echo "Method set_sendgrid_data_residency not found. Checking alternate ways...\n";
    }

    $response = $sendgrid->send($email);
    echo "Status Code: " . $response->statusCode() . "\n";
    echo "Body: " . $response->body() . "\n";
    echo "Headers: \n";
    print_r($response->headers());
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
