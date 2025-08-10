<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'product_id',
        'quantity',
        'last_updated',
    ];

    /**
     * Relationship: Inventory → Outlet
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Relationship: Inventory → Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
