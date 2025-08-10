<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'unit',
        'price',
    ];

    /**
     * Relationship: Product → Inventories
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Relationship: Product → Sales
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
