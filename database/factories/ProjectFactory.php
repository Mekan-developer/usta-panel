<?php

namespace Database\Factories;

use App\Enums\ProjectType;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $techStacks = [
            ['Laravel', 'Vue', 'Tailwind CSS'],
            ['Flutter', 'Dart', 'Firebase'],
            ['React Native', 'TypeScript', 'Node.js'],
            ['Laravel', 'Inertia.js', 'Vue', 'MySQL'],
            ['Flutter', 'Laravel API', 'PostgreSQL'],
            ['Electron', 'Vue', 'SQLite'],
        ];

        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(ProjectType::cases()),
            'thumbnail' => null,
            'screenshots' => [],
            'web_url' => fake()->optional(0.5)->url(),
            'play_store_url' => fake()->optional(0.3)->url(),
            'app_store_url' => fake()->optional(0.3)->url(),
            'desktop_url' => fake()->optional(0.2)->url(),
            'tech_stack' => fake()->randomElement($techStacks),
            'is_active' => true,
            'is_private' => false,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => true,
        ]);
    }

    public function web(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ProjectType::Web,
            'web_url' => fake()->url(),
        ]);
    }

    public function mobile(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => fake()->randomElement([ProjectType::MobileAndroid, ProjectType::MobileIos, ProjectType::MobileBoth]),
        ]);
    }
}
