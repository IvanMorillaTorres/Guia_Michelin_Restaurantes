<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // ejecutar todos los seeders en orden
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            PaisSeeder::class,
            ComunidadSeeder::class,
            CiudadSeeder::class,
            EstiloSeeder::class,
            RestauranteSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
