<?php

namespace Database\Factories;

use App\Models\Server;
use App\Models\ServerMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServerMetric>
 */
class ServerMetricFactory extends Factory
{
    public function definition(): array
    {
        return [
            'server_id' => Server::factory(),
            'cpu_usage' => fake()->randomFloat(2, 1, 99),
            'ram_used' => fake()->numberBetween(512, 8192),
            'ram_total' => 8192,
            'disk_used' => fake()->numberBetween(10, 200),
            'disk_total' => 250,
            'uptime_seconds' => fake()->numberBetween(60, 2592000),
            'recorded_at' => fake()->dateTimeBetween('-24 hours', 'now'),
        ];
    }
}
