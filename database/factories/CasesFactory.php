<?php

namespace Database\Factories;

use App\Models\Cases;
use App\Models\Category;
use App\Models\Prahari;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cases>
 */
class CasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['open', 'in_progress', 'closed'];
        $states = ['DL', 'MH', 'KA', 'TN', 'UP', 'WB', 'GJ', 'RJ'];
        $locations = [
            'NH-8, New Delhi',
            'Marine Drive, Mumbai',
            'MG Road, Bangalore',
            'Mount Road, Chennai',
            'Mall Road, Lucknow',
            'Park Circus, Kolkata',
            'CG Road, Ahmedabad',
            'Station Road, Jaipur',
        ];

        return [
            'prahari_id' => Prahari::inRandomOrder()->first()->id ?? Prahari::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'vehicle_number' => fake()->randomElement($states) . '-' . fake()->numerify('##') . '-' . fake()->bothify('??') . '-' . fake()->numerify('####'),
            'location' => fake()->randomElement($locations),
            'evidence_file' => '/storage/evidence/case_' . fake()->numerify('###') . '.mp4',
            'status' => fake()->randomElement($statuses),
            'violation_datetime' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
