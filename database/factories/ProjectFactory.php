<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(3),
            'title_th' => 'โครงการ '.fake()->word(),
            'title_en' => fake()->words(3, true),
            'summary_th' => 'สรุปโครงการ',
            'status' => ProjectStatus::Completed,
            'is_concept' => false,
            'sort_order' => 0,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(['published_at' => null]);
    }

    public function concept(): static
    {
        return $this->state(['is_concept' => true, 'status' => ProjectStatus::Planning]);
    }
}
