<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'title_th' => 'บริการ '.fake()->word(),
            'title_en' => fake()->words(2, true),
            'summary_th' => 'สรุปบริการ',
            'summary_en' => fake()->sentence(),
            'sort_order' => 0,
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(['is_published' => false]);
    }
}
