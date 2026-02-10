<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Ciudad;
use App\Models\Estilo;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    // mostrar todos los restaurantes con filtros
    public function index(Request $request)
    {
        // empezamos la consulta cargando las relaciones
        $consulta = Restaurante::with(['ciudad', 'estilos', 'imagenPrincipal']);

        // filtro por ciudad
        if ($request->ciudad != '') {
            $consulta->where('id_ciudad', $request->ciudad);
        }

        // filtro por estilos de cocina
        if ($request->has('estilos') && is_array($request->estilos)) {
            $consulta->whereHas('estilos', function ($q) use ($request) {
                $q->whereIn('estilos.id_estilo', $request->estilos);
            });
        }

        // filtro por precio minimo
        if ($request->precio_min != '') {
            $consulta->where('precio_restaurante', '>=', $request->precio_min);
        }

        // filtro por precio maximo
        if ($request->precio_max != '') {
            $consulta->where('precio_restaurante', '<=', $request->precio_max);
        }

        // filtro por valoracion minima
        if ($request->valoracion_min != '') {
            $consulta->where('valoracion_restaurante', '>=', $request->valoracion_min);
        }

        // ordenar los resultados
        $orden = $request->get('orden', 'nombre');

        if ($orden == 'precio_asc') {
            $consulta->orderBy('precio_restaurante', 'asc');
        } elseif ($orden == 'precio_desc') {
            $consulta->orderBy('precio_restaurante', 'desc');
        } elseif ($orden == 'valoracion') {
            $consulta->orderBy('valoracion_restaurante', 'desc');
        } else {
            $consulta->orderBy('nombre_restaurante', 'asc');
        }

        // paginar de 12 en 12
        $restaurantes = $consulta->paginate(12)->withQueryString();

        // sacar datos para los filtros del formulario
        $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        $estilos = Estilo::orderBy('nombre_estilo')->get();

        // devolver la vista con los datos
        return view('restaurantes.index', compact('restaurantes', 'ciudades', 'estilos'));
    }

    // mostrar un restaurante en detalle
    public function mostrar($slug)
    {
        // buscar el restaurante por su slug
        $restaurante = Restaurante::with(['ciudad', 'estilos', 'imagenes'])
            ->where('slug', $slug)
            ->firstOrFail();

        // buscar restaurantes parecidos de la misma ciudad
        $parecidos = Restaurante::with(['ciudad', 'estilos', 'imagenPrincipal'])
            ->where('id_restaurante', '!=', $restaurante->id_restaurante)
            ->where('id_ciudad', $restaurante->id_ciudad)
            ->limit(4)
            ->get();

        // devolver la vista
        return view('restaurantes.mostrar', compact('restaurante', 'parecidos'));
    }
}
