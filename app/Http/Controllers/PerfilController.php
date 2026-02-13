<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function mostrar()
    {
        $usuario = Auth::user();

        $guardados = $usuario->restaurantesGuardados()
            ->with(['ciudad.comunidad.pais', 'estilos', 'imagenPrincipal'])
            ->orderBy('restaurantes_guardados.created_at', 'desc')
            ->get();

        return view('perfil', compact('usuario', 'guardados'));
    }
}
