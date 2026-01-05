<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryUnit>
 */
class InventoryUnitFactory extends Factory
{
    protected $model = InventoryUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'serial_number' => fake()->unique()->numerify('SN#######'),
            'photo' => null,
            'condition_status' => fake()->randomElement(['available', 'in_use', 'maintenance', 'damaged']),
            'current_holder' => fake()->name(),
            'qr_code' => null,
        ];
    }
}

