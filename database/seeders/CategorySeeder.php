<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Alta cocina', 'descripcion' => 'Restaurantes de alta gastronomía'],
            ['nombre' => 'Bistró', 'descripcion' => 'Restaurantes informales de calidad'],
            ['nombre' => 'Gastrobar', 'descripcion' => 'Bares con propuesta gastronómica'],
            ['nombre' => 'Terraza', 'descripcion' => 'Con espacio exterior'],
            ['nombre' => 'Vistas', 'descripcion' => 'Con vistas panorámicas'],
            ['nombre' => 'Romantico', 'descripcion' => 'Ambiente íntimo y romántico'],
            ['nombre' => 'Grupo', 'descripcion' => 'Ideal para grupos'],
            ['nombre' => 'Familiar', 'descripcion' => 'Ambiente familiar'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'nombre' => $category['nombre'],
                'slug' => Str::slug($category['nombre']),
                'descripcion' => $category['descripcion']
            ]);
        }
    }
}
