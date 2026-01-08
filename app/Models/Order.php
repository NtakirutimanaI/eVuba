<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Import this
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory; // <-- Now it works

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'status',
        'payment_method',
        'payment_status',
        'transaction_ref'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: Order belongs to a User (customer)
     */

}
