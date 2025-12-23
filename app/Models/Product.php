<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Columns that can be mass assigned
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'product_code',
        'image',   // store image path
        'status',   // published/unpublished
        'unit_price'
    ];

    /**
     * Product belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Product has many stock_in records
     */
    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'product_id');
    }

    /**
     * Product has many stock_out records
     */
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'product_id');
    }

    /**
     * Product has one optional inventory log
     */
    public function inventoryLog()
    {
        return $this->hasOne(InventoryLog::class);
    }

    /**
     * Get remaining stock for the product
     */
    public function getRemainingStockAttribute()
    {
        $stockInQty = $this->stockIns ? $this->stockIns->sum('quantity') : 0;
        $stockOutQty = $this->stockOuts ? $this->stockOuts->sum('quantity') : 0;

        return $stockInQty - $stockOutQty;
    }

    /**
     * Get total profit/loss for this product
     * (using unit_cost from stock_ins and unit_price from stock_outs)
     */
    public function getTotalProfitAttribute()
    {
        $profit = 0;
        foreach ($this->stockOuts as $stockOut) {
            // Find related stock_in records to calculate unit cost
            $stockIn = $this->stockIns->first(); // simplest approach: first stock_in
            $unitCost = $stockIn ? $stockIn->unit_cost : 0;
            $profit += ($stockOut->unit_price - $unitCost) * $stockOut->quantity;
        }
        return $profit;
    }

    /**
     * Accessor: get product image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Check if product is published
     */
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }

}
