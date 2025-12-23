<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReply extends Model
{
    protected $fillable = ['message_us_id', 'user_id', 'reply_content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messageUs()
    {
        return $this->belongsTo(MessageUs::class);
    }
}
