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
            'first_name' => 'N\'faly',
            'last_name' => 'Kaba',
            'email' => 'nfalykaba@gmail.com',
        ]);
        \App\Models\User::factory()->create([
            'first_name' => 'Dian',
            'last_name' => 'Bah',
            'email' => 'bamadian@gmail.com',
        ]);
        \App\Models\User::factory()->create([
            'first_name' => 'Amadou',
            'last_name' => 'Diallo',
            'email' => 'amadio.diallo@gmail.com',
        ]);
    }
}
