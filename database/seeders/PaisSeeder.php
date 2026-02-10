<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    // meter los paises en la base de datos
    public function run(): void
    {
        $paises = [
            ['nombre' => 'España', 'abreviatura' => 'ES'],
            ['nombre' => 'Francia', 'abreviatura' => 'FR'],
            ['nombre' => 'Italia', 'abreviatura' => 'IT'],
        ];

        foreach ($paises as $pais) {
            Pais::create($pais);
        }
    }
}
