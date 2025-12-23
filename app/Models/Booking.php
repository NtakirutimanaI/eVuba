<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'service_id',
        'title',
        'description',
        'booking_date',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to Employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id'); 
        // assumes employees are stored in users table
    }

    // Relation to Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
