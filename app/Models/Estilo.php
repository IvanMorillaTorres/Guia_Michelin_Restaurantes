<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estilo extends Model
{
    protected $table = 'estilos';
    protected $primaryKey = 'id_estilo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estilo',
        'descripcion_estilo',
    ];

    // un estilo puede tener muchos restaurantes (muchos a muchos)
    public function restaurantes()
    {
        return $this->belongsToMany(
            Restaurante::class,
            'rest_estilos',
            'id_estilo',
            'id_restaurante',
            'id_estilo',
            'id_restaurante'
        );
    }
}
