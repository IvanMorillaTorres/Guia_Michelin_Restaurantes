<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurante extends Model
{
    protected $table = 'restaurantes';
    protected $primaryKey = 'id_restaurante';
    public $timestamps = false;

    protected $fillable = [
        'nombre_restaurante',
        'slug',
        'telefono_restaurante',
        'precio_restaurante',
        'descripcion_restaurante',
        'valoracion_restaurante',
        'web_real_restaurante',
        'latitud',
        'longitud',
        'id_ciudad',
    ];

    protected $casts = [
        'precio_restaurante' => 'decimal:2',
        'valoracion_restaurante' => 'decimal:1',
    ];

    // cuando se crea un restaurante, se genera el slug automaticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($restaurante) {
            if (empty($restaurante->slug)) {
                $restaurante->slug = Str::slug($restaurante->nombre_restaurante);
            }
        });
    }

    // un restaurante pertenece a una ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    // un restaurante tiene muchos estilos de cocina (muchos a muchos)
    public function estilos()
    {
        return $this->belongsToMany(
            Estilo::class,
            'rest_estilos',
            'id_restaurante',
            'id_estilo',
            'id_restaurante',
            'id_estilo'
        );
    }

    // un restaurante tiene muchas imagenes
    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'id_restaurante', 'id_restaurante');
    }

    // imagen principal (la primera que se subio)
    public function imagenPrincipal()
    {
        return $this->hasOne(Imagen::class, 'id_restaurante', 'id_restaurante')->oldest('id_imagenes');
    }

    // un restaurante tiene muchas valoraciones
    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class, 'id_restaurante', 'id_restaurante');
    }

    // usuarios que lo tienen guardado
    public function guardadoPorUsuarios()
    {
        return $this->belongsToMany(
            User::class,
            'restaurantes_guardados',
            'id_restaurante',
            'id_users',
            'id_restaurante',
            'id_users'
        )->withTimestamps();
    }

    // comentarios del restaurante
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_restaurante', 'id_restaurante');
    }
}
