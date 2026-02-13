<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentarios';
    protected $primaryKey = 'id_comentario';

    protected $fillable = [
        'id_restaurante',
        'id_users',
        'texto',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}
