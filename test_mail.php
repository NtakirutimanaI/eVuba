<?php

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Test', function ($msg) {
        $msg->to('faustin.ndayishimiye40@gmail.com')->subject('Test');
    });
    echo "Success\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
