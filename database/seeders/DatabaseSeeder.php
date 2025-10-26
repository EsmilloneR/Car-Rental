<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'esmillone.rosal1@gmail.com'],
            [
                'name' => 'Esmillone Rosal',
                'password' => bcrypt('yana210'),
                'role' => 'admin',
                'address' => 'Cagayan De Oro City',
                'nationality' => 'Filipino',
                'id_type' => 'N/A',
                'id_number' => 'FAFA210',
                'id_pictures' => [],
                'phone_number' => '9276676985',
            ]
        );
    }
}
