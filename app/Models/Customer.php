<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Default primary key (auto-increment)
    public $incrementing = true;
    protected $keyType = 'int';

    // Relationships
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'user_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
