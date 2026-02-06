<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion'
    ];

    /**
     * Restaurantes en esta categoría
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_category');
    }
}
