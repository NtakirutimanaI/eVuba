<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'stock_in';

    // Fillable fields
    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',       // added user_id for relationship
        'quantity',
        'unit_cost',
        'total_cost',
        'stock_in_date',
        'type',
        'note',
    ];

    // Cast stock_in_date to Carbon object
    protected $casts = [
        'stock_in_date' => 'date',
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relationship with User (who added the stock)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
