<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // crear usuario administrador si no existe
        if (!User::where('email', 'admin@michelin.com')->exists()) {
            User::create([
                'nombre' => 'Admin',
                'apellido1' => 'Michelin',
                'apellido2' => '',
                'nombre_del_atributo' => 'admin',
                'email' => 'admin@michelin.com',
                'password_hash' => Hash::make('qazQAZ123'),
                'telefono' => '600000000',
                'nacimiento' => '1990-01-01',
                'estado' => 'activo',
                'id_rol' => 1, // Administrador
            ]);
        }
    }
}
