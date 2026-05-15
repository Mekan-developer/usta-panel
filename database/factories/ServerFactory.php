<?php

namespace Database\Factories;

use App\Enums\ServerStatus;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Server>
 */
class ServerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true).' Server',
            'url' => fake()->url().'/metrics',
            'token' => Str::random(40),
            'status' => ServerStatus::Unknown,
            'last_checked_at' => null,
            'last_metrics' => null,
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ServerStatus::Online,
            'last_checked_at' => now(),
            'last_metrics' => [
                'cpu_usage' => fake()->randomFloat(2, 5, 80),
                'ram_used' => fake()->numberBetween(1024, 8192),
                'ram_total' => 8192,
                'disk_used' => fake()->numberBetween(20, 200),
                'disk_total' => 250,
                'uptime_seconds' => fake()->numberBetween(3600, 2592000),
            ],
        ]);
    }

    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ServerStatus::Offline,
            'last_checked_at' => now()->subMinutes(5),
            'last_metrics' => null,
        ]);
    }
}
