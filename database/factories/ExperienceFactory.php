<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => fake()->company(),
            'duration' => '2020 — 2023',
            'role' => [
                'en' => fake()->jobTitle(),
                'ru' => 'Разработчик',
                'tk' => 'Döredijisi',
            ],
            'description' => [
                'en' => fake()->sentence(),
                'ru' => 'Описание опыта работы.',
                'tk' => 'Iş tejribesi.',
            ],
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
