<?php
require 'vendor/autoload.php';

// Load actual info from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['MAIL_PASSWORD'];
$fromEmail = $_ENV['MAIL_FROM_ADDRESS'];

$email = new \SendGrid\Mail\Mail();
$email->setFrom($fromEmail, "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo($fromEmail, "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html",
    "<strong>and easy to do anywhere, even with PHP</strong>"
);

$sendgrid = new \SendGrid($apiKey);

try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
