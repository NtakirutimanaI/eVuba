<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'id',        // use user id as primary key
        'name',
        'email',
        'phone',
        'position',
        'specialization',
        'department',
        'created_at',
        'updated_at',
    ];

    // Use custom primary key type
    public $incrementing = false; // because we use user id as primary key
    protected $keyType = 'int';

    /**
     * Relationship: An employee can have many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'employee_id', 'id');
    }

    /**
     * Relationship: Employee belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id', 'id');
    }
}
