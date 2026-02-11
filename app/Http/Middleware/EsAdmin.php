<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EsAdmin
{
    // solo deja pasar si el usuario esta logueado y es admin (id_rol = 1)
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->id_rol != 1) {
            abort(403, 'No tienes permisos de administrador.');
        }

        return $next($request);
    }
}
