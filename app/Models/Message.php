<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'sender_name',
        'subject',
        'body',
        'url',
        'read_at',
    ];
}
