<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'serial_number',
        'photo',
        'condition_status',
        'current_holder',
    ];

    // Relasi: Satu InventoryUnit dimiliki oleh satu InventoryItem
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}