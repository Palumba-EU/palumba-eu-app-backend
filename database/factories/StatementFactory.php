<?php

namespace Database\Factories;

use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statement>
 */
class StatementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'statement' => fake()->sentence(),
            'details' => fake()->sentences(3, true),
            'footnote' => fake()->sentence(),
            'sort_index' => fake()->numberBetween(1, 100),
            'emojis' => fake()->emoji(),
            'published' => true,
            'election_id' => Election::factory(),
        ];
    }
}
