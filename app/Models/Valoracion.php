<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $table = 'valoraciones';
    protected $primaryKey = 'id_valoracion';

    protected $fillable = [
        'id_restaurante',
        'id_users',
        'puntuacion',
    ];

    protected $casts = [
        'puntuacion' => 'integer',
    ];

    // la valoracion pertenece a un restaurante
    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    // la valoracion pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}
