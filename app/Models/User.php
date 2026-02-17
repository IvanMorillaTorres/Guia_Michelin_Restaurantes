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

    // un usuario tiene muchas valoraciones
    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class, 'id_users', 'id_users');
    }

    // restaurantes guardados (favoritos)
    public function restaurantesGuardados()
    {
        return $this->belongsToMany(
            Restaurante::class,
            'restaurantes_guardados',
            'id_users',
            'id_restaurante',
            'id_users',
            'id_restaurante'
        )->withTimestamps();
    }

    // comentarios del usuario
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_users', 'id_users');
    }

    // nombre de la columna del password
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    // devuelve el hash del password pa la auth
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // accessor: si piden password devolvemos password_hash
    public function getPasswordAttribute()
    {
        return $this->password_hash ?? null;
    }

    // mutator: cuando meten password lo hasheamos y lo guardamos en password_hash
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = \Illuminate\Support\Facades\Hash::make($value);
    }
}
