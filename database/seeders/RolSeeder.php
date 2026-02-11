<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolSeeder extends Seeder
{
    // meter los roles en la base de datos
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema'],
            ['nombre' => 'Editor', 'descripcion' => 'Puede crear y editar restaurantes'],
            ['nombre' => 'Usuario', 'descripcion' => 'Usuario registrado normal'],
        ];

        foreach ($roles as $rol) {
            $rol['slug'] = Str::slug($rol['nombre']);
            Rol::create($rol);
        }
    }
}
