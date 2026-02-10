<?php

namespace Database\Seeders;

use App\Models\Restaurante;
use App\Models\Ciudad;
use App\Models\Estilo;
use App\Models\Imagen;
use Illuminate\Database\Seeder;

class RestauranteSeeder extends Seeder
{
    // meter los restaurantes en la base de datos
    public function run(): void
    {
        // buscamos las ciudades
        $barcelona = Ciudad::where('nombre_ciudad', 'Barcelona')->first();
        $girona = Ciudad::where('nombre_ciudad', 'Girona')->first();

        // lista de restaurantes con sus datos
        $restaurantes = [
            [
                'nombre_restaurante' => 'Lasarte',
                'slug' => 'lasarte',
                'telefono_restaurante' => '+34 934 45 32 42',
                'precio_restaurante' => 245.00,
                'descripcion_restaurante' => 'Cocina de alta gastronomia con influencias internacionales.',
                'valoracion_restaurante' => 4.8,
                'web_real_restaurante' => 'https://www.restaurantlasarte.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Cocina Creativa', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'ABaC',
                'slug' => 'abac',
                'telefono_restaurante' => '+34 933 19 66 00',
                'precio_restaurante' => 220.00,
                'descripcion_restaurante' => 'Restaurante de vanguardia que fusiona creatividad y tradicion.',
                'valoracion_restaurante' => 4.7,
                'web_real_restaurante' => 'https://www.abacbarcelona.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Cocina Creativa', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'Moments',
                'slug' => 'moments',
                'telefono_restaurante' => '+34 933 51 87 81',
                'precio_restaurante' => 195.00,
                'descripcion_restaurante' => 'Elegancia y tradicion catalana en el corazon de Barcelona.',
                'valoracion_restaurante' => 4.6,
                'web_real_restaurante' => 'https://www.mandarinoriental.com/barcelona',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Catalana', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'Cinc Sentits',
                'slug' => 'cinc-sentits',
                'telefono_restaurante' => '+34 933 23 94 90',
                'precio_restaurante' => 135.00,
                'descripcion_restaurante' => 'Cocina de mercado con producto local y de temporada.',
                'valoracion_restaurante' => 4.5,
                'web_real_restaurante' => 'https://www.cincsentits.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['De mercado', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'Disfrutar',
                'slug' => 'disfrutar',
                'telefono_restaurante' => '+34 933 48 68 96',
                'precio_restaurante' => 210.00,
                'descripcion_restaurante' => 'Cocina vanguardista y creativa de los discipulos de Ferran Adria.',
                'valoracion_restaurante' => 4.9,
                'web_real_restaurante' => 'https://www.disfrutarbarcelona.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Cocina Creativa', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'Hofmann',
                'slug' => 'hofmann',
                'telefono_restaurante' => '+34 932 18 71 65',
                'precio_restaurante' => 110.00,
                'descripcion_restaurante' => 'Cocina mediterranea con toques modernos.',
                'valoracion_restaurante' => 4.4,
                'web_real_restaurante' => 'https://www.hofmann-bcn.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Mediterránea', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'Koy Shunka',
                'slug' => 'koy-shunka',
                'telefono_restaurante' => '+34 934 12 79 39',
                'precio_restaurante' => 145.00,
                'descripcion_restaurante' => 'El mejor japones de Barcelona.',
                'valoracion_restaurante' => 4.6,
                'web_real_restaurante' => 'https://www.koyshunka.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Japonesa'],
            ],
            [
                'nombre_restaurante' => 'Can Jubany',
                'slug' => 'can-jubany',
                'telefono_restaurante' => '+34 938 44 10 34',
                'precio_restaurante' => 55.00,
                'descripcion_restaurante' => 'Cocina catalana de autor con productos locales.',
                'valoracion_restaurante' => 4.3,
                'web_real_restaurante' => 'https://www.canjubany.com',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['Catalana', 'De mercado'],
            ],
            [
                'nombre_restaurante' => 'El Celler de Can Roca',
                'slug' => 'el-celler-de-can-roca',
                'telefono_restaurante' => '+34 972 22 21 57',
                'precio_restaurante' => 255.00,
                'descripcion_restaurante' => 'Uno de los mejores restaurantes del mundo.',
                'valoracion_restaurante' => 5.0,
                'web_real_restaurante' => 'https://www.cellercanroca.com',
                'id_ciudad' => $girona->id_ciudad,
                'estilos' => ['Cocina Creativa', 'Contemporánea'],
            ],
            [
                'nombre_restaurante' => 'Botafumeiro',
                'slug' => 'botafumeiro',
                'telefono_restaurante' => '+34 932 18 42 30',
                'precio_restaurante' => 65.00,
                'descripcion_restaurante' => 'Referencia en mariscos y pescados frescos.',
                'valoracion_restaurante' => 4.2,
                'web_real_restaurante' => 'https://www.botafumeiro.es',
                'id_ciudad' => $barcelona->id_ciudad,
                'estilos' => ['De mercado', 'Tradicional'],
            ],
        ];

        // recorremos el array y creamos cada restaurante
        foreach ($restaurantes as $datos) {
            // guardamos los estilos aparte
            $nombresEstilos = $datos['estilos'];
            unset($datos['estilos']);

            // creamos el restaurante
            $restaurante = Restaurante::create($datos);

            // le asignamos los estilos
            foreach ($nombresEstilos as $nombreEstilo) {
                $estilo = Estilo::where('nombre_estilo', $nombreEstilo)->first();
                if ($estilo) {
                    $restaurante->estilos()->attach($estilo->id_estilo);
                }
            }

            // le asignamos la imagen si existe
            $rutaImagen = 'assets/restaurantes/' . $restaurante->slug . '.jpg';
            if (file_exists(public_path($rutaImagen))) {
                Imagen::create([
                    'imagen' => $rutaImagen,
                    'id_restaurante' => $restaurante->id_restaurante,
                ]);
            }
        }
    }
}
