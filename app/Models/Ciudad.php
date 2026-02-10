<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $primaryKey = 'id_ciudad';
    public $timestamps = false;

    protected $fillable = [
        'nombre_ciudad',
        'codigo_postal_ciudad',
        'id_comunidad',
    ];

    // una ciudad pertenece a una comunidad
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'id_comunidad', 'id_comunidad');
    }

    // una ciudad tiene muchos restaurantes
    public function restaurantes()
    {
        return $this->hasMany(Restaurante::class, 'id_ciudad', 'id_ciudad');
    }
}
