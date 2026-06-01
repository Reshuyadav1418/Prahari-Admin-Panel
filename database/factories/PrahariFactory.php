<?php

namespace Database\Factories;

use App\Models\Prahari;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prahari>
 */
class PrahariFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'aadhar_number' => fake()->unique()->numerify('################'),
            'phone' => fake()->unique()->numerify('9#########'),
            'bank_account_number' => fake()->unique()->regexify('ACCT[0-9]{15}'),
            'status' => 1,
        ];
    }
}
