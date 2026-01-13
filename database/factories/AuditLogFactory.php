<?php

namespace Database\Factories;

use App\Models\IpAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'ip_address_id' => IpAddress::factory()->create()->id,
            'action' => $this->faker->randomElement(['created', 'update', 'deleted', 'login', 'logout']),
            'old_values' => null,
            'new_values' => null,
        ];
    }
}
