<?php

namespace Database\Factories;

use App\Enums\SkillCategory;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'abbr' => strtoupper(fake()->lexify('??')),
            'category' => fake()->randomElement(SkillCategory::cases())->value,
            'percent' => fake()->numberBetween(50, 100),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
