<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryUnit extends Model
{
    use HasFactory;

    // 🔴 PENTING: karena ID bukan auto increment
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inventory_item_id',
        'serial_number',
        'photo',
        'condition_status',
        'current_holder',
    ];

    /**
     * Auto-generate ID: 00001, 00002, dst
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $lastId = DB::table('inventory_units')
                    ->orderBy('id', 'desc')
                    ->value('id');

                $model->id = $lastId
                    ? str_pad(((int) $lastId) + 1, 5, '0', STR_PAD_LEFT)
                    : '00001';
            }
        });
    }

    // Relasi: Satu InventoryUnit dimiliki oleh satu InventoryItem
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
