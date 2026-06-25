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

        User::factory()->create([
            'name' => 'Alonso Admin',
            'email' => 'admin@tienda.com',
            'password' => bcrypt('sistemas'), // Cambia esto por la contraseña que quieras
            'role' => 'admin',
        ]);
    }
}
