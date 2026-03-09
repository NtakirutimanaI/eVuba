<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$to = "faustinnet12@gmail.com";
$subject = "Test PHP Mail - eVuba";
$message = "<h1>Hello!</h1><p>This is a test of the PHP mail() function.</p>";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
$headers .= "From: E-VUBA CONNECT <faustinnet12@gmail.com>" . "\r\n" .
    "Reply-To: faustinnet12@gmail.com" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Mail Sent Successfully.";
} else {
    echo "Failed to Send Mail. Please check your PHP/sendmail configuration.";
}
