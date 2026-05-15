<?php

namespace Database\Factories;

use App\Enums\LearningCategory;
use App\Enums\LearningStatus;
use App\Models\LearningTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LearningTopic>
 */
class LearningTopicFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(LearningStatus::cases());

        return [
            'title' => fake()->words(2, true),
            'category' => fake()->randomElement(LearningCategory::cases()),
            'status' => $status,
            'period' => $status === LearningStatus::Current ? null : fake()->date('Y-m'),
            'progress' => $status === LearningStatus::Current ? fake()->numberBetween(10, 90) : 0,
            'notes' => fake()->optional()->sentence(),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function studied(): static
    {
        return $this->state(fn () => [
            'status' => LearningStatus::Studied,
            'period' => fake()->date('Y-m'),
            'progress' => 100,
        ]);
    }

    public function current(): static
    {
        return $this->state(fn () => [
            'status' => LearningStatus::Current,
            'period' => null,
            'progress' => fake()->numberBetween(10, 90),
        ]);
    }

    public function planned(): static
    {
        return $this->state(fn () => [
            'status' => LearningStatus::Planned,
            'period' => fake()->date('Y-m'),
            'progress' => 0,
        ]);
    }
}
