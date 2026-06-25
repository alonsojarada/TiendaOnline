<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;                  // <-- Importación del modelo de usuarios
use Illuminate\Support\Facades\Hash; // <-- Importación de la herramienta de encriptación

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiamos si quedó algún intento previo a medias
        User::where('email', 'alo.jarada@live.com')->delete();

        // Creamos el usuario administrador correctamente
        User::create([
            'name' => 'Alonso',
            'email' => 'alo.jarada@live.com',
            'password' => Hash::make('sistemas'), 
            'role' => 'admin',
        ]);
    }
}