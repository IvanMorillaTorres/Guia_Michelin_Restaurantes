<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'nombre' => 'Barcelona',
                'provincia' => 'Barcelona',
                'comunidad_autonoma' => 'Cataluña',
                'pais' => 'España',
                'latitud' => 41.3851,
                'longitud' => 2.1734
            ],
            [
                'nombre' => 'Girona',
                'provincia' => 'Girona',
                'comunidad_autonoma' => 'Cataluña',
                'pais' => 'España',
                'latitud' => 41.9794,
                'longitud' => 2.8214
            ],
            [
                'nombre' => 'Tarragona',
                'provincia' => 'Tarragona',
                'comunidad_autonoma' => 'Cataluña',
                'pais' => 'España',
                'latitud' => 41.1189,
                'longitud' => 1.2445
            ],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
