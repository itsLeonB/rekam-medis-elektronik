<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => 'admin123'
        ]);

        $this->call([
            UserSeeder::class,
            CodeSystemSeeder::class,
            ValueSetSeeder::class,
            IdFhirResourceSeeder::class,
        ]);
    }
}
