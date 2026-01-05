<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InventoryUnit;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';

    protected $fillable = [
        'name',
        'total_stock',
        'available_stock',
        'damaged_stock',
    ];

    protected $casts = [
        'total_stock' => 'integer',
        'available_stock' => 'integer',
        'damaged_stock' => 'integer',
    ];

    public function units()
    {
        return $this->hasMany(InventoryUnit::class);
    }
}