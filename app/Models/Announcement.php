<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'target_role',
        'is_active',
    ];

    // Optional: users who have read this announcement
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_announcements');
    }
}
