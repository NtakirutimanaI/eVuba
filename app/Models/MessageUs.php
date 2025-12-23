<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageUs extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'message_us';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'message',
    ];

    public function replies()
    {
        return $this->hasMany(MessageReply::class, 'message_us_id');
    }
}
