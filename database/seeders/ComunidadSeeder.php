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
        // Creamos/obtenemos países base (por si el seeder se ejecuta aislado)
        $espana = Pais::firstOrCreate(['nombre' => 'España'], ['abreviatura' => 'ES']);
        $francia = Pais::firstOrCreate(['nombre' => 'Francia'], ['abreviatura' => 'FR']);
        $italia = Pais::firstOrCreate(['nombre' => 'Italia'], ['abreviatura' => 'IT']);

        $comunidades = [
            // España
            ['nombre_comunidad' => 'Cataluña', 'abreviatura_comunidad' => 'CAT', 'id_pais' => $espana->id_pais],
            ['nombre_comunidad' => 'Comunidad Valenciana', 'abreviatura_comunidad' => 'VAL', 'id_pais' => $espana->id_pais],
            ['nombre_comunidad' => 'País Vasco', 'abreviatura_comunidad' => 'PVA', 'id_pais' => $espana->id_pais],
            ['nombre_comunidad' => 'Comunidad de Madrid', 'abreviatura_comunidad' => 'MAD', 'id_pais' => $espana->id_pais],

            // Francia (regiones ejemplo)
            ['nombre_comunidad' => 'Île-de-France', 'abreviatura_comunidad' => 'IDF', 'id_pais' => $francia->id_pais],
            ['nombre_comunidad' => 'Provence-Alpes-Côte d\'Azur', 'abreviatura_comunidad' => 'PACA', 'id_pais' => $francia->id_pais],

            // Italia (regiones ejemplo)
            ['nombre_comunidad' => 'Lazio', 'abreviatura_comunidad' => 'LAZ', 'id_pais' => $italia->id_pais],
            ['nombre_comunidad' => 'Lombardia', 'abreviatura_comunidad' => 'LOM', 'id_pais' => $italia->id_pais],
        ];

        foreach ($comunidades as $comunidad) {
            Comunidad::firstOrCreate(
                ['nombre_comunidad' => $comunidad['nombre_comunidad'], 'id_pais' => $comunidad['id_pais']],
                ['abreviatura_comunidad' => $comunidad['abreviatura_comunidad']]
            );
        }
    }
}
