<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'city_id',
        'direccion',
        'codigo_postal',
        'latitud',
        'longitud',
        'telefono',
        'email',
        'website',
        'precio_medio',
        'rango_precios',
        'estrellas_michelin',
        'estrella_verde',
        'bib_gourmand',
        'horario',
        'dia_cierre',
        'capacidad',
        'terraza',
        'parking',
        'accesible',
        'chef',
        'valoracion',
        'activo'
    ];

    protected $casts = [
        'precio_medio' => 'decimal:2',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
        'estrella_verde' => 'boolean',
        'bib_gourmand' => 'boolean',
        'terraza' => 'boolean',
        'parking' => 'boolean',
        'accesible' => 'boolean',
        'activo' => 'boolean',
        'valoracion' => 'decimal:1',
    ];

    /**
     * Generar slug automáticamente
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = Str::slug($restaurant->nombre);
            }
        });
    }

    /**
     * Ciudad del restaurante
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Tipos de cocina
     */
    public function cuisineTypes()
    {
        return $this->belongsToMany(CuisineType::class, 'restaurant_cuisine');
    }

    /**
     * Categorías
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'restaurant_category');
    }

    /**
     * Imágenes del restaurante
     */
    public function images()
    {
        return $this->hasMany(RestaurantImage::class)->orderBy('orden');
    }

    /**
     * Imagen principal
     */
    public function mainImage()
    {
        return $this->hasOne(RestaurantImage::class)->where('es_principal', true);
    }

    /**
     * Verificar si tiene estrellas Michelin
     */
    public function hasMichelinStars(): bool
    {
        return $this->estrellas_michelin > 0;
    }

    /**
     * Obtener símbolo de rango de precios
     */
    public function getPriceRangeSymbolAttribute(): string
    {
        return $this->rango_precios ?? '€€';
    }

    /**
     * Scope: Restaurantes activos
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: Con estrellas Michelin
     */
    public function scopeWithMichelinStars($query, $stars = null)
    {
        if ($stars !== null) {
            return $query->where('estrellas_michelin', $stars);
        }
        return $query->where('estrellas_michelin', '>', 0);
    }

    /**
     * Scope: Con Bib Gourmand
     */
    public function scopeBibGourmand($query)
    {
        return $query->where('bib_gourmand', true);
    }

    /**
     * Scope: Por ciudad
     */
    public function scopeInCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Scope: Ordenar por distancia (requiere coordenadas de referencia)
     */
    public function scopeOrderByDistance($query, $lat, $lng)
    {
        return $query->selectRaw("
            *, 
            ( 6371 * acos( cos( radians(?) ) *
              cos( radians( latitud ) ) *
              cos( radians( longitud ) - radians(?) ) +
              sin( radians(?) ) *
              sin( radians( latitud ) ) )
            ) AS distance
        ", [$lat, $lng, $lat])
        ->orderBy('distance');
    }
}
