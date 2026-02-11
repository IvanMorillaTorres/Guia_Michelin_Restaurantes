<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurante;
use App\Models\Ciudad;
use App\Models\Estilo;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminRestauranteController extends Controller
{
    // listado de restaurantes en el panel admin
    public function index(Request $request)
    {
        $consulta = Restaurante::with(['ciudad', 'estilos', 'imagenPrincipal']);

        // busqueda rapida por nombre
        if ($request->busqueda != '') {
            $consulta->where('nombre_restaurante', 'like', "%{$request->busqueda}%");
        }

        $restaurantes = $consulta->orderBy('nombre_restaurante')->paginate(15)->withQueryString();

        return view('admin.restaurantes.index', compact('restaurantes'));
    }

    // formulario para crear un restaurante nuevo
    public function crear()
    {
        $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        $estilos = Estilo::orderBy('nombre_estilo')->get();

        return view('admin.restaurantes.crear', compact('ciudades', 'estilos'));
    }

    // guardar el restaurante nuevo en la base de datos
    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre_restaurante' => 'required|string|max:255',
            'telefono_restaurante' => 'nullable|string|max:20',
            'precio_restaurante' => 'nullable|numeric|min:0',
            'descripcion_restaurante' => 'nullable|string',
            'valoracion_restaurante' => 'nullable|numeric|min:0|max:5',
            'web_real_restaurante' => 'nullable|url|max:255',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'estilos' => 'nullable|array',
            'estilos.*' => 'exists:estilos,id_estilo',
            'imagenes.*' => 'nullable|image|max:2048',
        ]);

        // crear el restaurante
        $restaurante = Restaurante::create([
            'nombre_restaurante' => $datos['nombre_restaurante'],
            'slug' => Str::slug($datos['nombre_restaurante']),
            'telefono_restaurante' => $datos['telefono_restaurante'] ?? null,
            'precio_restaurante' => $datos['precio_restaurante'] ?? null,
            'descripcion_restaurante' => $datos['descripcion_restaurante'] ?? null,
            'valoracion_restaurante' => $datos['valoracion_restaurante'] ?? null,
            'web_real_restaurante' => $datos['web_real_restaurante'] ?? null,
            'id_ciudad' => $datos['id_ciudad'],
        ]);

        // asociar estilos de cocina
        if (!empty($datos['estilos'])) {
            $restaurante->estilos()->attach($datos['estilos']);
        }

        // subir imagenes si las hay
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $ruta = $imagen->store('restaurantes', 'public');
                Imagen::create([
                    'imagen' => $ruta,
                    'id_restaurante' => $restaurante->id_restaurante,
                ]);
            }
        }

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante creado correctamente.');
    }

    // formulario para editar un restaurante existente
    public function editar($id)
    {
        $restaurante = Restaurante::with(['estilos', 'imagenes'])->findOrFail($id);
        $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        $estilos = Estilo::orderBy('nombre_estilo')->get();

        return view('admin.restaurantes.editar', compact('restaurante', 'ciudades', 'estilos'));
    }

    // actualizar el restaurante en la base de datos
    public function actualizar(Request $request, $id)
    {
        $restaurante = Restaurante::findOrFail($id);

        $datos = $request->validate([
            'nombre_restaurante' => 'required|string|max:255',
            'telefono_restaurante' => 'nullable|string|max:20',
            'precio_restaurante' => 'nullable|numeric|min:0',
            'descripcion_restaurante' => 'nullable|string',
            'valoracion_restaurante' => 'nullable|numeric|min:0|max:5',
            'web_real_restaurante' => 'nullable|url|max:255',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'estilos' => 'nullable|array',
            'estilos.*' => 'exists:estilos,id_estilo',
            'imagenes.*' => 'nullable|image|max:2048',
        ]);

        // actualizar datos del restaurante
        $restaurante->update([
            'nombre_restaurante' => $datos['nombre_restaurante'],
            'slug' => Str::slug($datos['nombre_restaurante']),
            'telefono_restaurante' => $datos['telefono_restaurante'] ?? null,
            'precio_restaurante' => $datos['precio_restaurante'] ?? null,
            'descripcion_restaurante' => $datos['descripcion_restaurante'] ?? null,
            'valoracion_restaurante' => $datos['valoracion_restaurante'] ?? null,
            'web_real_restaurante' => $datos['web_real_restaurante'] ?? null,
            'id_ciudad' => $datos['id_ciudad'],
        ]);

        // sincronizar estilos
        $restaurante->estilos()->sync($datos['estilos'] ?? []);

        // subir imagenes nuevas si las hay
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $ruta = $imagen->store('restaurantes', 'public');
                Imagen::create([
                    'imagen' => $ruta,
                    'id_restaurante' => $restaurante->id_restaurante,
                ]);
            }
        }

        // eliminar imagenes marcadas
        if ($request->has('eliminar_imagenes')) {
            foreach ($request->eliminar_imagenes as $idImagen) {
                $imagen = Imagen::find($idImagen);
                if ($imagen) {
                    // borrar el archivo si esta en storage
                    if (!str_starts_with($imagen->imagen, 'assets/')) {
                        Storage::disk('public')->delete($imagen->imagen);
                    }
                    $imagen->delete();
                }
            }
        }

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante actualizado correctamente.');
    }

    // eliminar un restaurante
    public function eliminar($id)
    {
        $restaurante = Restaurante::findOrFail($id);

        // eliminar imagenes del storage
        foreach ($restaurante->imagenes as $imagen) {
            if (!str_starts_with($imagen->imagen, 'assets/')) {
                Storage::disk('public')->delete($imagen->imagen);
            }
        }

        // eliminar estilos asociados (pivot)
        $restaurante->estilos()->detach();

        // eliminar el restaurante (las imagenes se borran por cascade)
        $restaurante->delete();

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante eliminado correctamente.');
    }
}
