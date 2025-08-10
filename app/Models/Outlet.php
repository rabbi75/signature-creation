<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'distributor_id',
    ];

    /**
     * Relationship: Outlet → Distributor
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Relationship: Outlet → Inventories
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Relationship: Outlet → Sales
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
