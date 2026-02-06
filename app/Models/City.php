<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'nombre',
        'provincia',
        'comunidad_autonoma',
        'pais',
        'latitud',
        'longitud'
    ];

    protected $casts = [
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    /**
     * Restaurantes de esta ciudad
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
