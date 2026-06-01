<?php

namespace Database\Factories;

use App\Models\Challan;
use App\Models\Cases;
use App\Models\Category;
use App\Models\Prahari;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Challan>
 */
class ChallanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'paid', 'cancelled'];

        return [
            'prahari_id' => Prahari::inRandomOrder()->first()->id ?? Prahari::factory(),
            'case_id' => Cases::inRandomOrder()->first()->id ?? Cases::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'vehicle_number' => fake()->regexify('[A-Z]{2}-[0-9]{2}-[A-Z]{2}-[0-9]{4}'),
            'amount' => fake()->randomFloat(2, 200, 1500),
            'status' => fake()->randomElement($statuses),
            'challan_date' => fake()->dateBetween('-30 days', 'today'),
        ];
    }
}
