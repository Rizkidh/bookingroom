<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * Defaultnya adalah bentuk plural dari nama model (inventory_items).
     *
     * @var string
     */
    protected $table = 'inventory_items';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'total_stock',
        'available_stock',
        'damaged_stock',
    ];

    /**
     * Atribut yang harus diubah ke tipe data native.
     *
     * @var array
     */
    protected $casts = [
        // Pastikan kolom stock diubah menjadi integer
        'total_stock' => 'integer',
        'available_stock' => 'integer',
        'damaged_stock' => 'integer',
    ];
}