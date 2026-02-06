<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuisineType extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion'
    ];

    /**
     * Restaurantes con este tipo de cocina
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_cuisine');
    }
}
