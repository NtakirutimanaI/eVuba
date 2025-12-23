<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // Table name (optional if Laravel follows convention 'schedules')
    protected $table = 'schedules';

    // The attributes that are mass assignable
    protected $fillable = [
        'title',
        'start_time',
        'end_time',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
