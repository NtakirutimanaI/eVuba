<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stock;

    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    public function build()
    {
        return $this->subject('Stock Alert Notification')
                    ->view('emails.stock_notification');
    }
}
