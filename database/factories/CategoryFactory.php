<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $violationTypes = [
            'Speeding',
            'Rash Driving',
            'No Parking Zone',
            'Traffic Signal Violation',
            'Invalid Documentation',
            'Helmet Not Worn',
            'Seatbelt Violation',
            'Vehicle Pollution',
        ];

        return [
            'name' => fake()->randomElement($violationTypes),
            'amount' => fake()->randomFloat(2, 200, 1500),
            'description' => fake()->sentence(),
            'status' => 1,
        ];
    }
}
