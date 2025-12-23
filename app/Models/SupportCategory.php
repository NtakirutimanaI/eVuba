<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', // optional
    ];

    /**
     * Relationship: A category can have many tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }
}
