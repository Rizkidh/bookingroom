<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'total_stock' => fake()->numberBetween(0, 100),
            'available_stock' => function (array $attributes) {
                return fake()->numberBetween(0, $attributes['total_stock']);
            },
            'damaged_stock' => function (array $attributes) {
                return fake()->numberBetween(0, $attributes['total_stock'] - $attributes['available_stock']);
            },
        ];
    }
}

