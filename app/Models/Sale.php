<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'product_id',
        'date',
        'quantity_sold',
        'total_price',
    ];

    /**
     * Relationship: Sale → Outlet
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Relationship: Sale → Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
