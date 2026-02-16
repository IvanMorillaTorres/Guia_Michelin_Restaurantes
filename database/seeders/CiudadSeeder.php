<?php

namespace Database\Seeders;

use App\Models\Pais;
use App\Models\Comunidad;
use App\Models\Ciudad;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    // meter las ciudades en la base de datos
    public function run(): void
    {
        // Aseguramos países (por si se ejecuta aislado)
        $espana = Pais::firstOrCreate(['nombre' => 'España'], ['abreviatura' => 'ES']);
        $francia = Pais::firstOrCreate(['nombre' => 'Francia'], ['abreviatura' => 'FR']);
        $italia = Pais::firstOrCreate(['nombre' => 'Italia'], ['abreviatura' => 'IT']);

        // Aseguramos comunidades base (por si se ejecuta aislado)
        $cataluna = Comunidad::firstOrCreate(
            ['nombre_comunidad' => 'Cataluña', 'id_pais' => $espana->id_pais],
            ['abreviatura_comunidad' => 'CAT']
        );
        $madrid = Comunidad::firstOrCreate(
            ['nombre_comunidad' => 'Comunidad de Madrid', 'id_pais' => $espana->id_pais],
            ['abreviatura_comunidad' => 'MAD']
        );
        $valencia = Comunidad::firstOrCreate(
            ['nombre_comunidad' => 'Comunidad Valenciana', 'id_pais' => $espana->id_pais],
            ['abreviatura_comunidad' => 'VAL']
        );

        $idf = Comunidad::firstOrCreate(
            ['nombre_comunidad' => 'Île-de-France', 'id_pais' => $francia->id_pais],
            ['abreviatura_comunidad' => 'IDF']
        );
        $paca = Comunidad::firstOrCreate(
            ['nombre_comunidad' => "Provence-Alpes-Côte d'Azur", 'id_pais' => $francia->id_pais],
            ['abreviatura_comunidad' => 'PACA']
        );

        $lazio = Comunidad::firstOrCreate(
            ['nombre_comunidad' => 'Lazio', 'id_pais' => $italia->id_pais],
            ['abreviatura_comunidad' => 'LAZ']
        );
        $lombardia = Comunidad::firstOrCreate(
            ['nombre_comunidad' => 'Lombardia', 'id_pais' => $italia->id_pais],
            ['abreviatura_comunidad' => 'LOM']
        );

        $ciudades = [
            // Cataluña
            ['nombre_ciudad' => 'Barcelona', 'codigo_postal_ciudad' => '08001', 'id_comunidad' => $cataluna->id_comunidad],
            ['nombre_ciudad' => 'Girona', 'codigo_postal_ciudad' => '17001', 'id_comunidad' => $cataluna->id_comunidad],
            ['nombre_ciudad' => 'Tarragona', 'codigo_postal_ciudad' => '43001', 'id_comunidad' => $cataluna->id_comunidad],

            // Madrid
            ['nombre_ciudad' => 'Madrid', 'codigo_postal_ciudad' => '28001', 'id_comunidad' => $madrid->id_comunidad],
            ['nombre_ciudad' => 'Alcalá de Henares', 'codigo_postal_ciudad' => '28801', 'id_comunidad' => $madrid->id_comunidad],

            // Comunidad Valenciana
            ['nombre_ciudad' => 'València', 'codigo_postal_ciudad' => '46001', 'id_comunidad' => $valencia->id_comunidad],
            ['nombre_ciudad' => 'Alicante', 'codigo_postal_ciudad' => '03001', 'id_comunidad' => $valencia->id_comunidad],

            // Île-de-France
            ['nombre_ciudad' => 'Paris', 'codigo_postal_ciudad' => '75001', 'id_comunidad' => $idf->id_comunidad],
            ['nombre_ciudad' => 'Versailles', 'codigo_postal_ciudad' => '78000', 'id_comunidad' => $idf->id_comunidad],

            // PACA
            ['nombre_ciudad' => 'Marseille', 'codigo_postal_ciudad' => '13001', 'id_comunidad' => $paca->id_comunidad],
            ['nombre_ciudad' => 'Nice', 'codigo_postal_ciudad' => '06000', 'id_comunidad' => $paca->id_comunidad],

            // Lazio
            ['nombre_ciudad' => 'Roma', 'codigo_postal_ciudad' => '00118', 'id_comunidad' => $lazio->id_comunidad],

            // Lombardia
            ['nombre_ciudad' => 'Milano', 'codigo_postal_ciudad' => '20121', 'id_comunidad' => $lombardia->id_comunidad],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::firstOrCreate(
                ['nombre_ciudad' => $ciudad['nombre_ciudad'], 'id_comunidad' => $ciudad['id_comunidad']],
                ['codigo_postal_ciudad' => $ciudad['codigo_postal_ciudad']]
            );
        }
    }
}
