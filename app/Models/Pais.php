<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';
    protected $primaryKey = 'id_pais';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'abreviatura',
    ];

    // un pais tiene muchas comunidades
    public function comunidades()
    {
        return $this->hasMany(Comunidad::class, 'id_pais', 'id_pais');
    }
}
