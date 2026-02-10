<?php

namespace Database\Seeders;

use App\Models\Estilo;
use Illuminate\Database\Seeder;

class EstiloSeeder extends Seeder
{
    // meter los estilos de cocina en la base de datos
    public function run(): void
    {
        $estilos = [
            ['nombre_estilo' => 'Cocina Creativa', 'descripcion_estilo' => 'Cocina innovadora y de autor'],
            ['nombre_estilo' => 'Mediterránea', 'descripcion_estilo' => 'Cocina del mediterraneo'],
            ['nombre_estilo' => 'Catalana', 'descripcion_estilo' => 'Cocina tradicional catalana'],
            ['nombre_estilo' => 'Contemporánea', 'descripcion_estilo' => 'Cocina moderna y actual'],
            ['nombre_estilo' => 'De mercado', 'descripcion_estilo' => 'Productos frescos de temporada'],
            ['nombre_estilo' => 'Japonesa', 'descripcion_estilo' => 'Cocina japonesa y sushi'],
            ['nombre_estilo' => 'Fusión', 'descripcion_estilo' => 'Mezcla de estilos culinarios'],
            ['nombre_estilo' => 'Tradicional', 'descripcion_estilo' => 'Cocina clasica española'],
            ['nombre_estilo' => 'Internacional', 'descripcion_estilo' => 'Cocina de diversas culturas'],
            ['nombre_estilo' => 'Asiática', 'descripcion_estilo' => 'Cocina de Asia'],
        ];

        foreach ($estilos as $estilo) {
            Estilo::create($estilo);
        }
    }
}
