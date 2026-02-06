<?php

namespace Database\Seeders;

use App\Models\CuisineType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CuisineTypeSeeder extends Seeder
{
    public function run(): void
    {
        $cuisines = [
            ['nombre' => 'Cocina Creativa', 'descripcion' => 'Cocina innovadora y de autor'],
            ['nombre' => 'Mediterránea', 'descripcion' => 'Cocina del mediterráneo'],
            ['nombre' => 'Catalana', 'descripcion' => 'Cocina tradicional catalana'],
            ['nombre' => 'Contemporánea', 'descripcion' => 'Cocina moderna y actual'],
            ['nombre' => 'De mercado', 'descripcion' => 'Productos frescos de temporada'],
            ['nombre' => 'Japonesa', 'descripcion' => 'Cocina japonesa y sushi'],
            ['nombre' => 'Fusión', 'descripcion' => 'Mezcla de estilos culinarios'],
            ['nombre' => 'Tradicional', 'descripcion' => 'Cocina clásica española'],
            ['nombre' => 'Internacional', 'descripcion' => 'Cocina de diversas culturas'],
            ['nombre' => 'Asiática', 'descripcion' => 'Cocina de Asia'],
        ];

        foreach ($cuisines as $cuisine) {
            CuisineType::create([
                'nombre' => $cuisine['nombre'],
                'slug' => Str::slug($cuisine['nombre']),
                'descripcion' => $cuisine['descripcion']
            ]);
        }
    }
}
