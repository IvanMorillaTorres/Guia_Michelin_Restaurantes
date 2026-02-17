<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Ciudad;
use App\Models\Estilo;
use App\Models\Valoracion;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Pais;
use App\Models\Comunidad;

class RestauranteController extends Controller
{
    // mostrar todos los restaurantes con filtros
    public function index(Request $request)
    {
        // empezamos la consulta cargando las relaciones
        $consulta = Restaurante::with(['ciudad.comunidad.pais', 'estilos', 'imagenPrincipal']);

        // busqueda por texto (nombre de restaurante o ciudad)
        if ($request->busqueda != '') {
            $busqueda = $request->busqueda;
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('nombre_restaurante', 'like', "%{$busqueda}%")
                  ->orWhereHas('ciudad', function ($qc) use ($busqueda) {
                      $qc->where('nombre_ciudad', 'like', "%{$busqueda}%");
                  });
            });
        }

        // filtros de ubicacion

        // filtro por pais
        if ($request->filled('pais')) {
            $consulta->whereHas('ciudad.comunidad', function ($q) use ($request) {
                $q->where('id_pais', $request->pais);
            });
        }

        // filtro por comunidad autonoma
        if ($request->filled('comunidad')) {
            $consulta->whereHas('ciudad', function ($q) use ($request) {
                $q->where('id_comunidad', $request->comunidad);
            });
        }

        // filtro por ciudad
        if ($request->filled('ciudad')) {
            $consulta->where('id_ciudad', $request->ciudad);
        }

        // cargamos las listas para los selects

        // paises: siempre todos
        $paises = Pais::orderBy('nombre')->get();

        // comunidades: dependen del pais
        if ($request->filled('pais')) {
            $comunidades = Comunidad::where('id_pais', $request->pais)->orderBy('nombre_comunidad')->get();
        } else {
            $comunidades = Comunidad::orderBy('nombre_comunidad')->get();
        }

        // ciudades: dependen de la comunidad o del pais
        if ($request->filled('comunidad')) {
            $ciudades = Ciudad::where('id_comunidad', $request->comunidad)->orderBy('nombre_ciudad')->get();
        } elseif ($request->filled('pais')) {
            $ciudades = Ciudad::whereHas('comunidad', function($q) use ($request) {
                $q->where('id_pais', $request->pais);
            })->orderBy('nombre_ciudad')->get();
        } else {
            $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        }

        // resto de filtros

        // filtro por estilos de cocina
        $estilosSeleccionados = $request->input('estilos');
        if (!empty($estilosSeleccionados)) {
            if (!is_array($estilosSeleccionados)) {
                $estilosSeleccionados = [$estilosSeleccionados];
            }

            $estilosSeleccionados = array_values(array_filter($estilosSeleccionados, function ($valor) {
                return $valor !== null && $valor !== '';
            }));

            if (!empty($estilosSeleccionados)) {
                $consulta->whereHas('estilos', function ($q) use ($estilosSeleccionados) {
                    $q->whereIn('estilos.id_estilo', $estilosSeleccionados);
                });
            }
        }

        // filtro por precio minimo
        if ($request->precio_min != '') {
            $consulta->where('precio_restaurante', '>=', $request->precio_min);
        }

        // filtro por precio maximo
        if ($request->precio_max != '') {
            $consulta->where('precio_restaurante', '<=', $request->precio_max);
        }

        // filtro por rango de precio (pill select)
        if ($request->precio_rango != '') {
            $rango = $request->precio_rango;
            if ($rango === '100+') {
                $consulta->where('precio_restaurante', '>=', 100);
            } else {
                $partes = explode('-', $rango);
                if (count($partes) === 2) {
                    $consulta->whereBetween('precio_restaurante', [(int)$partes[0], (int)$partes[1]]);
                }
            }
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

        // sacamos los ids de restaurantes guardados por el usuario (pa pintar el corazon)
        $guardadosIds = [];
        if (Auth::check()) {
            $guardadosIds = Auth::user()
                ->restaurantesGuardados()
                ->pluck('restaurantes.id_restaurante')
                ->toArray();
        }

        // sacar datos para los filtros del formulario (estilos siempre todos)
        $estilos = Estilo::orderBy('nombre_estilo')->get();

        // devolver la vista con los datos
        return view('restaurantes.index', compact('restaurantes', 'paises', 'comunidades', 'ciudades', 'estilos', 'guardadosIds'));
    }

    // mostrar un restaurante en detalle
    public function mostrar($slug)
    {
        // buscar el restaurante por su slug
        $restaurante = Restaurante::with(['ciudad.comunidad.pais', 'estilos', 'imagenes'])
            ->withCount('valoraciones')
            ->where('slug', $slug)
            ->firstOrFail();

        $valoracionUsuario = null;
        if (Auth::check()) {
            $valoracionUsuario = $restaurante->valoraciones()
                ->where('id_users', Auth::id())
                ->value('puntuacion');
        }

        $estaGuardado = false;
        if (Auth::check()) {
            $estaGuardado = Auth::user()
                ->restaurantesGuardados()
                ->where('restaurantes.id_restaurante', $restaurante->id_restaurante)
                ->exists();
        }

        $comentarios = Comentario::with('usuario')
            ->where('id_restaurante', $restaurante->id_restaurante)
            ->orderBy('created_at', 'desc')
            ->get();

        // buscamos restaurantes parecidos en la misma ciudad
        $parecidos = Restaurante::with(['ciudad.comunidad.pais', 'estilos', 'imagenPrincipal'])
            ->where('id_restaurante', '!=', $restaurante->id_restaurante)
            ->where('id_ciudad', $restaurante->id_ciudad)
            ->limit(4)
            ->get();

        // devolver vista
        return view('restaurantes.mostrar', compact('restaurante', 'parecidos', 'valoracionUsuario', 'estaGuardado', 'comentarios'));
    }

    // guardar un comentario
    public function comentar(Request $request, $slug)
    {
        $datos = $request->validate([
            'texto' => 'required|string|min:2|max:1000',
        ]);

        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();

        // si no ha valorado no puede comentar
        $puntuacion = Valoracion::where('id_restaurante', $restaurante->id_restaurante)
            ->where('id_users', Auth::id())
            ->value('puntuacion');

        if (empty($puntuacion)) {
            return redirect()
                ->route('restaurantes.mostrar', $restaurante->slug)
                ->withErrors(['texto' => 'Debes dejar una valoración (estrellas) antes de comentar.'])
                ->withInput();
        }

        // solo un comentario por restaurante
        $yaComentado = Comentario::where('id_restaurante', $restaurante->id_restaurante)
            ->where('id_users', Auth::id())
            ->exists();

        if ($yaComentado) {
            return redirect()
                ->route('restaurantes.mostrar', $restaurante->slug)
                ->withErrors(['texto' => 'Solo puedes comentar una vez en este restaurante.']);
        }

        Comentario::create([
            'id_restaurante' => $restaurante->id_restaurante,
            'id_users' => Auth::id(),
            'puntuacion' => (int) $puntuacion,
            'texto' => $datos['texto'],
        ]);

        return redirect()
            ->route('restaurantes.mostrar', $restaurante->slug)
            ->with('comentario_ok', 'Comentario publicado.');
    }

    // guardar/quitar un restaurante de guardados
    public function toggleGuardado(Request $request, $slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
        $usuario = Auth::user();

        $ya = $usuario->restaurantesGuardados()
            ->where('restaurantes.id_restaurante', $restaurante->id_restaurante)
            ->exists();

        if ($ya) {
            $usuario->restaurantesGuardados()->detach($restaurante->id_restaurante);
        } else {
            $usuario->restaurantesGuardados()->attach($restaurante->id_restaurante);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'guardado' => !$ya,
            ]);
        }

        return back();
    }

    // guardar/actualizar una valoracion del usuario
    public function valorar(Request $request, $slug)
    {
        $datos = $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
        ]);

        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();

        Valoracion::updateOrCreate(
            [
                'id_restaurante' => $restaurante->id_restaurante,
                'id_users' => Auth::id(),
            ],
            [
                'puntuacion' => $datos['puntuacion'],
            ]
        );

        // recalculamos la media y la guardamos
        $media = Valoracion::where('id_restaurante', $restaurante->id_restaurante)->avg('puntuacion');
        $restaurante->valoracion_restaurante = round((float) $media, 1);
        $restaurante->save();

        $count = Valoracion::where('id_restaurante', $restaurante->id_restaurante)->count();

        // si es AJAX respondemos en JSON
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'media' => (float) $restaurante->valoracion_restaurante,
                'count' => $count,
                'user' => (int) $datos['puntuacion'],
            ]);
        }

        return redirect()
            ->route('restaurantes.mostrar', $restaurante->slug)
            ->with('valoracion_ok', '¡Gracias! Tu valoración se ha guardado.');
    }
}
