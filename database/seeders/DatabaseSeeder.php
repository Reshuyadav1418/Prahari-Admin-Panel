<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        //for Admin
        User::factory()->create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'role' => 'admin',
    'password' => bcrypt('password')
]);
//for user
      User::factory()->create([
    'name' => 'Normal User',
    'email' => 'user@example.com',
    'role' => 'user',
    'password' => bcrypt('password')
]);

        // Run seeders in dependency order
        $this->call([
            CategorySeeder::class,
            PrahariSeeder::class,
            CaseSeeder::class,
            ChallanSeeder::class,
        ]);
    }
}
