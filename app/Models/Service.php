<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'employee_id',
        'image',
        'is_published',
        'price',
        'duration',
    ];

    // Relation to employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
