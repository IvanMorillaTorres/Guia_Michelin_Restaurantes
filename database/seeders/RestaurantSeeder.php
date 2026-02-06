<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\City;
use App\Models\CuisineType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    private function generateSlug($nombre)
    {
        return strtolower(str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['-', 'a', 'e', 'i', 'o', 'u', 'n'], $nombre));
    }

    public function run(): void
    {
        $barcelona = City::where('nombre', 'Barcelona')->first();
        $girona = City::where('nombre', 'Girona')->first();
        
        $restaurants = [
            [
                'nombre' => 'Lasarte',
                'slug' => 'lasarte',
                'descripcion' => 'Cocina de alta gastronomía con influencias internacionales. Restaurante emblemático con 3 estrellas Michelin, liderado por el chef Martín Berasategui.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer de Mallorca, 259',
                'codigo_postal' => '08008',
                'latitud' => 41.3925,
                'longitud' => 2.1626,
                'telefono' => '+34 934 45 32 42',
                'website' => 'https://www.restaurantlasarte.com',
                'precio_medio' => 245.00,
                'rango_precios' => '€€€€',
                'estrellas_michelin' => 3,
                'chef' => 'Martín Berasategui',
                'valoracion' => 4.8,
                'terraza' => false,
                'parking' => true,
                'accesible' => true,
                'horario' => 'Martes a Sábado: 13:30-15:00 y 20:30-22:00',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['Cocina Creativa', 'Contemporánea'],
                'categories' => ['Alta cocina', 'Romantico']
            ],
            [
                'nombre' => 'ABaC',
                'slug' => 'abac',
                'descripcion' => 'Restaurante de vanguardia que fusiona creatividad y tradición en cada plato. Liderado por el chef Jordi Cruz.',
                'city_id' => $barcelona->id,
                'direccion' => 'Avinguda del Tibidabo, 1',
                'codigo_postal' => '08022',
                'latitud' => 41.4075,
                'longitud' => 2.1340,
                'telefono' => '+34 933 19 66 00',
                'website' => 'https://www.abacbarcelona.com',
                'precio_medio' => 220.00,
                'rango_precios' => '€€€€',
                'estrellas_michelin' => 3,
                'chef' => 'Jordi Cruz',
                'valoracion' => 4.7,
                'terraza' => true,
                'parking' => true,
                'accesible' => true,
                'horario' => 'Martes a Sábado: 13:00-15:30 y 20:00-22:30',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['Cocina Creativa', 'Contemporánea'],
                'categories' => ['Alta cocina', 'Vistas']
            ],
            [
                'nombre' => 'Moments',
                'slug' => 'moments',
                'descripcion' => 'Elegancia y tradición catalana en el corazón de Barcelona. Dos estrellas Michelin bajo la dirección de Raül Balam.',
                'city_id' => $barcelona->id,
                'direccion' => 'Passeig de Gràcia, 38-40',
                'codigo_postal' => '08007',
                'latitud' => 41.3909,
                'longitud' => 2.1652,
                'telefono' => '+34 933 51 87 81',
                'website' => 'https://www.mandarinoriental.com/barcelona',
                'precio_medio' => 195.00,
                'rango_precios' => '€€€€',
                'estrellas_michelin' => 2,
                'chef' => 'Raül Balam',
                'valoracion' => 4.6,
                'terraza' => false,
                'parking' => true,
                'accesible' => true,
                'horario' => 'Martes a Sábado: 13:30-15:00 y 20:30-22:00',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['Catalana', 'Contemporánea'],
                'categories' => ['Alta cocina', 'Romantico']
            ],
            [
                'nombre' => 'Cinc Sentits',
                'slug' => 'cinc-sentits',
                'descripcion' => 'Cocina de mercado con producto local y de temporada. Una estrella Michelin.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer d\'Aribau, 58',
                'codigo_postal' => '08011',
                'latitud' => 41.3867,
                'longitud' => 2.1594,
                'telefono' => '+34 933 23 94 90',
                'website' => 'https://www.cincsentits.com',
                'precio_medio' => 135.00,
                'rango_precios' => '€€€',
                'estrellas_michelin' => 1,
                'chef' => 'Jordi Artal',
                'valoracion' => 4.5,
                'terraza' => false,
                'parking' => false,
                'accesible' => true,
                'horario' => 'Martes a Sábado: 13:30-15:30 y 20:30-22:30',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['De mercado', 'Contemporánea'],
                'categories' => ['Alta cocina']
            ],
            [
                'nombre' => 'Disfrutar',
                'slug' => 'disfrutar',
                'descripcion' => 'Cocina vanguardista y creativa de los discípulos de Ferran Adrià. Dos estrellas Michelin.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer de Villarroel, 163',
                'codigo_postal' => '08036',
                'latitud' => 41.3789,
                'longitud' => 2.1515,
                'telefono' => '+34 933 48 68 96',
                'website' => 'https://www.disfrutarbarcelona.com',
                'precio_medio' => 210.00,
                'rango_precios' => '€€€€',
                'estrellas_michelin' => 2,
                'chef' => 'Oriol Castro, Eduard Xatruch, Mateu Casañas',
                'valoracion' => 4.9,
                'terraza' => true,
                'parking' => false,
                'accesible' => true,
                'horario' => 'Martes a Sábado: 13:00-15:00 y 20:00-22:00',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['Cocina Creativa', 'Contemporánea'],
                'categories' => ['Alta cocina']
            ],
            [
                'nombre' => 'Hofmann',
                'slug' => 'hofmann',
                'descripcion' => 'Cocina mediterránea con toques modernos. Una estrella Michelin y escuela culinaria de prestigio.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer d\'Enric Granados, 51',
                'codigo_postal' => '08008',
                'latitud' => 41.3904,
                'longitud' => 2.1602,
                'telefono' => '+34 932 18 71 65',
                'website' => 'https://www.hofmann-bcn.com',
                'precio_medio' => 110.00,
                'rango_precios' => '€€€',
                'estrellas_michelin' => 1,
                'chef' => 'Mey Hofmann',
                'valoracion' => 4.4,
                'terraza' => false,
                'parking' => false,
                'accesible' => true,
                'horario' => 'Lunes a Viernes: 13:30-15:30 y 20:30-22:30',
                'dia_cierre' => 'Sábado y Domingo',
                'cuisines' => ['Mediterránea', 'Contemporánea'],
                'categories' => ['Alta cocina']
            ],
            [
                'nombre' => 'Koy Shunka',
                'slug' => 'koy-shunka',
                'descripcion' => 'El mejor japonés de Barcelona. Una estrella Michelin con auténtica cocina nipona.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer de Copons, 7',
                'codigo_postal' => '08002',
                'latitud' => 41.3846,
                'longitud' => 2.1757,
                'telefono' => '+34 934 12 79 39',
                'website' => 'https://www.koyshunka.com',
                'precio_medio' => 145.00,
                'rango_precios' => '€€€',
                'estrellas_michelin' => 1,
                'chef' => 'Hideki Matsuhisa',
                'valoracion' => 4.6,
                'terraza' => false,
                'parking' => false,
                'accesible' => false,
                'horario' => 'Martes a Sábado: 13:30-15:00 y 20:30-22:30',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['Japonesa'],
                'categories' => ['Alta cocina']
            ],
            [
                'nombre' => 'Can Jubany',
                'slug' => 'can-jubany',
                'descripcion' => 'Cocina catalana de autor con productos locales. Bib Gourmand.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer de la Creu, 4',
                'codigo_postal' => '08551',
                'latitud' => 41.9206,
                'longitud' => 2.2364,
                'telefono' => '+34 938 44 10 34',
                'website' => 'https://www.canjubany.com',
                'precio_medio' => 55.00,
                'rango_precios' => '€€',
                'estrellas_michelin' => 0,
                'bib_gourmand' => true,
                'chef' => 'Nandu Jubany',
                'valoracion' => 4.3,
                'terraza' => true,
                'parking' => true,
                'accesible' => true,
                'horario' => 'Martes a Domingo: 13:00-16:00 y 20:00-23:00',
                'dia_cierre' => 'Lunes',
                'cuisines' => ['Catalana', 'De mercado'],
                'categories' => ['Bistró', 'Familiar']
            ],
            [
                'nombre' => 'El Celler de Can Roca',
                'slug' => 'el-celler-de-can-roca',
                'descripcion' => 'Tres estrellas Michelin. Considerado uno de los mejores restaurantes del mundo por los hermanos Roca.',
                'city_id' => $girona->id,
                'direccion' => 'Carrer de Can Sunyer, 48',
                'codigo_postal' => '17007',
                'latitud' => 41.9900,
                'longitud' => 2.8350,
                'telefono' => '+34 972 22 21 57',
                'website' => 'https://www.cellercanroca.com',
                'precio_medio' => 255.00,
                'rango_precios' => '€€€€',
                'estrellas_michelin' => 3,
                'estrella_verde' => true,
                'chef' => 'Joan, Josep y Jordi Roca',
                'valoracion' => 5.0,
                'terraza' => true,
                'parking' => true,
                'accesible' => true,
                'horario' => 'Martes a Sábado: 13:00-15:00 y 20:00-22:00',
                'dia_cierre' => 'Domingo y Lunes',
                'cuisines' => ['Cocina Creativa', 'Contemporánea'],
                'categories' => ['Alta cocina']
            ],
            [
                'nombre' => 'Botafumeiro',
                'slug' => 'botafumeiro',
                'descripcion' => 'Referencia en mariscos y pescados frescos. Bib Gourmand.',
                'city_id' => $barcelona->id,
                'direccion' => 'Carrer Gran de Gràcia, 81',
                'codigo_postal' => '08012',
                'latitud' => 41.3990,
                'longitud' => 2.1547,
                'telefono' => '+34 932 18 42 30',
                'website' => 'https://www.botafumeiro.es',
                'precio_medio' => 65.00,
                'rango_precios' => '€€',
                'estrellas_michelin' => 0,
                'bib_gourmand' => true,
                'valoracion' => 4.2,
                'terraza' => false,
                'parking' => false,
                'accesible' => true,
                'horario' => 'Todos los días: 13:00-01:00',
                'dia_cierre' => 'Ninguno',
                'cuisines' => ['De mercado', 'Tradicional'],
                'categories' => ['Grupo', 'Familiar']
            ],
        ];

        foreach ($restaurants as $restaurantData) {
            $cuisines = $restaurantData['cuisines'];
            $categories = $restaurantData['categories'];
            unset($restaurantData['cuisines'], $restaurantData['categories']);
            
            $restaurant = Restaurant::create($restaurantData);
            
            // Asignar tipos de cocina
            foreach ($cuisines as $cuisineName) {
                $cuisine = CuisineType::where('nombre', $cuisineName)->first();
                if ($cuisine) {
                    $restaurant->cuisineTypes()->attach($cuisine->id);
                }
            }
            
            // Asignar categorías
            foreach ($categories as $categoryName) {
                $category = Category::where('nombre', $categoryName)->first();
                if ($category) {
                    $restaurant->categories()->attach($category->id);
                }
            }
        }
    }
}
