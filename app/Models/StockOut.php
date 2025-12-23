<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    // Explicitly define table name
    protected $table = 'stock_out';

    // Fillable fields
    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',       // corresponds to DB column for unit cost
        'total_price',      // stored generated column in DB
        'customer_id',
        'supplier_id',
        'user_id',
        'stock_in_date',
        'stock_out_date',
        'type',
        'note',
    ];

    // Casts for proper formatting
    protected $casts = [
        'unit_price'      => 'decimal:2',
        'total_price'     => 'decimal:2',
        'stock_in_date'   => 'date',
        'stock_out_date'  => 'date',
    ];

    // Relationships

    // StockOut belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // StockOut belongs to a Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // StockOut belongs to the User who recorded it
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: if you want a supplier relation
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
