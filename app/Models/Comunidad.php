<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table = 'comunidades';
    protected $primaryKey = 'id_comunidad';
    public $timestamps = false;

    protected $fillable = [
        'nombre_comunidad',
        'abreviatura_comunidad',
        'id_pais',
    ];

    // una comunidad pertenece a un pais
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    // una comunidad tiene muchas ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'id_comunidad', 'id_comunidad');
    }
}
