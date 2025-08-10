<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'contact_info',
    ];

    /**
     * Relationship: Distributor → Outlets
     */
    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }
}
