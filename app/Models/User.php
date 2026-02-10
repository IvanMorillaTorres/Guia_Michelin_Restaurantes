<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';

    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'nombre_del_atributo',
        'email',
        'password_hash',
        'telefono',
        'nacimiento',
        'estado',
        'id_rol',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'nacimiento' => 'date',
    ];

    // un usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // para que laravel use password_hash en vez de password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
