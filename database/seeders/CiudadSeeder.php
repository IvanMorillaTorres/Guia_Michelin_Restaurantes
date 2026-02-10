<?php

namespace Database\Seeders;

use App\Models\Comunidad;
use App\Models\Ciudad;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    // meter las ciudades en la base de datos
    public function run(): void
    {
        // buscamos cataluña
        $cataluna = Comunidad::where('nombre_comunidad', 'Cataluña')->first();

        $ciudades = [
            ['nombre_ciudad' => 'Barcelona', 'codigo_postal_ciudad' => '08001', 'id_comunidad' => $cataluna->id_comunidad],
            ['nombre_ciudad' => 'Girona', 'codigo_postal_ciudad' => '17001', 'id_comunidad' => $cataluna->id_comunidad],
            ['nombre_ciudad' => 'Tarragona', 'codigo_postal_ciudad' => '43001', 'id_comunidad' => $cataluna->id_comunidad],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }
    }
}
