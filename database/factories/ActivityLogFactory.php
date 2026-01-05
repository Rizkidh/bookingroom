<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action' => fake()->randomElement(['CREATE', 'UPDATE', 'DELETE']),
            'model_type' => fake()->randomElement([
                'App\Models\InventoryItem',
                'App\Models\InventoryUnit',
            ]),
            'model_id' => fake()->numerify('#####'),
            'description' => fake()->sentence(),
            'old_values' => null,
            'new_values' => null,
            'note' => fake()->optional()->sentence(),
            'user_id' => User::factory(),
            'user_name' => fake()->name(),
            'user_role' => fake()->randomElement(['admin', 'pegawai']),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}

