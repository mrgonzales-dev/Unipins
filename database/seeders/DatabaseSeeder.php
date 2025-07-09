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

        User::factory()->create([
            'name' => 'Kenneth Gonzales',
            'email' => 'mrg@unipins.com',
            'password' => bcrypt('password'),
            'role' => 'seller',
            'phone' => '09123456789',
            'address' => 'Regidor Street, Daraga, Albay',
        ]);

        User::factory()->create([
            'name' => 'Jedec Cano',
            'email' => 'bossjed@unipins.com',
            'password' => bcrypt('password'),
            'role' => 'buyer',
            'phone' => '09123456789',
            'address' => 'Bogtong, Legazpi, Albay',
        ]);
    }




}
