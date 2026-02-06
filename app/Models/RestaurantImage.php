<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantImage extends Model
{
    protected $fillable = [
        'restaurant_id',
        'ruta',
        'alt_text',
        'es_principal',
        'orden'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    /**
     * Restaurante al que pertenece la imagen
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Obtener URL completa de la imagen
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->ruta);
    }
}
