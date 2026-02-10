<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'imagenes';
    protected $primaryKey = 'id_imagenes';
    public $timestamps = false;

    protected $fillable = [
        'imagen',
        'id_restaurante',
    ];

    // una imagen pertenece a un restaurante
    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'id_restaurante', 'id_restaurante');
    }

    // para sacar la url completa de la imagen
    public function getUrlAttribute()
    {
        if (str_starts_with($this->imagen, 'assets/')) {
            return asset($this->imagen);
        }
        return asset('storage/' . $this->imagen);
    }
}
