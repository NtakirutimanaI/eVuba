<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'sales';

    // The attributes that are mass assignable
    protected $fillable = [
        'product_id',
        'customer_id',
        'user_id',
        'quantity',
        'unit_price',
        'total_amount',
        'sale_date',
        'payment_method',
        'status',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Timestamps are enabled by default
    public $timestamps = true;
}
