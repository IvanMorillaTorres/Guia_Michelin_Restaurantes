<?php

namespace Database\Seeders;

use App\Models\Pais;
use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class ComunidadSeeder extends Seeder
{
    // meter las comunidades autonomas en la base de datos
    public function run(): void
    {
        // primero buscamos españa
        $espana = Pais::where('nombre', 'España')->first();

        $comunidades = [
            ['nombre_comunidad' => 'Cataluña', 'abreviatura_comunidad' => 'CAT', 'id_pais' => $espana->id_pais],
            ['nombre_comunidad' => 'Comunidad Valenciana', 'abreviatura_comunidad' => 'VAL', 'id_pais' => $espana->id_pais],
            ['nombre_comunidad' => 'País Vasco', 'abreviatura_comunidad' => 'PVA', 'id_pais' => $espana->id_pais],
        ];

        foreach ($comunidades as $comunidad) {
            Comunidad::create($comunidad);
        }
    }
}
