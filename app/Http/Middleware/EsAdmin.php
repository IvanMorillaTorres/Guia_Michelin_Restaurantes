<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EsAdmin
{
    // solo deja pasar si el usuario esta logueado y es admin (id_rol = 1)
    // se ejecuta en cada peticion que pasa por este middleware
    public function handle(Request $request, Closure $next)
    {
        // si no hay usuario logueado o no es admin, cortamos
        if (!Auth::check() || Auth::user()->id_rol != 1) {
            abort(403, 'No tienes permisos de administrador.');
        }

        return $next($request); // dejamos pasar la peticion
    }
}
