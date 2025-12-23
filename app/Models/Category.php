<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Table name (optional if table name is 'categories')
    protected $table = 'categories';

    // Fillable fields
    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
    return $this->hasMany(Product::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
