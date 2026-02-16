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
// Importamos las clases necesarias para el envío de correos
use Illuminate\Support\Facades\Mail;  // Facade para enviar correos
use Illuminate\Support\Facades\Log;   // Facade para registrar logs
use App\Mail\NotificacionCrudRestaurante;  // Nuestra clase Mailable personalizada

class AdminRestauranteController extends Controller
{
    // listado de restaurantes en el panel admin
    public function index(Request $request)
    {
        $consulta = Restaurante::with(['ciudad', 'estilos', 'imagenPrincipal']);

        // busqueda por nombre
        if ($request->filled('busqueda')) {
            $consulta->where('nombre_restaurante', 'like', "%{$request->busqueda}%");
        }

        // filtro por ciudad
        if ($request->filled('ciudad')) {
            $consulta->where('id_ciudad', $request->ciudad);
        }

        // filtro por estilo
        if ($request->filled('estilo')) {
            $consulta->whereHas('estilos', function ($q) use ($request) {
                $q->where('estilos.id_estilo', $request->estilo);
            });
        }

        // filtro por valoracion minima
        if ($request->filled('valoracion')) {
            $consulta->where('valoracion_restaurante', '>=', $request->valoracion);
        }

        // ordenacion
        $orden = $request->get('orden', 'id_restaurante');
        $dir = $request->get('dir', 'asc');
        $columasPermitidas = ['nombre_restaurante', 'precio_restaurante', 'valoracion_restaurante', 'id_restaurante'];
        if (!in_array($orden, $columasPermitidas)) $orden = 'nombre_restaurante';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'asc';

        $consulta->orderBy($orden, $dir);

        $restaurantes = $consulta->paginate(10)->withQueryString();
        $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        $estilos = Estilo::orderBy('nombre_estilo')->get();

        return view('admin.restaurantes.index', compact('restaurantes', 'ciudades', 'estilos'));
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
            'nombre_restaurante' => 'required|string|max:255|regex:/^(?!.*\\d)[\\p{L}][\\p{L}\\s\\-\\.&]*$/u',
            'telefono_restaurante' => 'required|regex:/^\\d{9}$/',
            'precio_restaurante' => 'nullable|numeric|min:0',
            'descripcion_restaurante' => 'nullable|string',
            'valoracion_restaurante' => 'nullable|numeric|min:0|max:5',
            'web_real_restaurante' => 'required|url|max:255',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'estilos' => 'required|array|min:1',
            'estilos.*' => 'exists:estilos,id_estilo',
            'imagenes.*' => 'nullable|image|max:2048',
        ], [
            'nombre_restaurante.required' => 'El nombre del restaurante es obligatorio.',
            'nombre_restaurante.regex' => 'El nombre del restaurante no puede contener números.',
            'telefono_restaurante.required' => 'El teléfono es obligatorio.',
            'telefono_restaurante.regex' => 'El teléfono debe tener exactamente 9 números.',
            'web_real_restaurante.required' => 'La página web es obligatoria.',
            'web_real_restaurante.url' => 'La página web debe ser una URL válida (ej. https://...).',
            'estilos.required' => 'Debes seleccionar al menos 1 estilo de cocina.',
            'estilos.min' => 'Debes seleccionar al menos 1 estilo de cocina.',
        ]);

        // crear el restaurante
        $datosRestaurante = [
            'nombre_restaurante' => $datos['nombre_restaurante'],
            'slug' => Str::slug($datos['nombre_restaurante']),
            'telefono_restaurante' => $datos['telefono_restaurante'] ?? null,
            'precio_restaurante' => $datos['precio_restaurante'] ?? null,
            'descripcion_restaurante' => $datos['descripcion_restaurante'] ?? null,
            'web_real_restaurante' => $datos['web_real_restaurante'] ?? null,
            'id_ciudad' => $datos['id_ciudad'],
        ];

        // solo agregar valoracion si tiene valor
        if (!empty($datos['valoracion_restaurante'])) {
            $datosRestaurante['valoracion_restaurante'] = $datos['valoracion_restaurante'];
        }

        $restaurante = Restaurante::create($datosRestaurante);

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

        // ===== ENVÍO DE CORREO ELECTRÓNICO =====
        // Enviamos un correo de notificación al administrador
        try {
            // Mail::to() - especifica el destinatario del correo
            // send() - envía el correo usando la clase Mailable
            // new NotificacionCrudRestaurante() - crea una instancia del correo con los datos
            Mail::to('marcnavarrojocs@gmail.com')->send(
                new NotificacionCrudRestaurante(
                    'crear',                    // Tipo de acción realizada
                    $restaurante->load('ciudad'), // Restaurante con relación ciudad cargada
                    auth()->user()              // Usuario autenticado que realizó la acción
                )
            );
        } catch (\Exception $e) {
            // Si falla el envío del correo, lo registramos en el log
            // pero NO detenemos la ejecución (el restaurante ya fue creado)
            Log::error('Error al enviar correo de notificación: ' . $e->getMessage());
        }
        // ===== FIN ENVÍO DE CORREO =====

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
            'nombre_restaurante' => 'required|string|max:255|regex:/^(?!.*\\d)[\\p{L}][\\p{L}\\s\\-\\.&]*$/u',
            'telefono_restaurante' => 'required|regex:/^\\d{9}$/',
            'precio_restaurante' => 'nullable|numeric|min:0',
            'descripcion_restaurante' => 'nullable|string',
            'valoracion_restaurante' => 'nullable|numeric|min:0|max:5',
            'web_real_restaurante' => 'required|url|max:255',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'estilos' => 'required|array|min:1',
            'estilos.*' => 'exists:estilos,id_estilo',
            'imagenes.*' => 'nullable|image|max:2048',
        ], [
            'nombre_restaurante.required' => 'El nombre del restaurante es obligatorio.',
            'nombre_restaurante.regex' => 'El nombre del restaurante no puede contener números.',
            'telefono_restaurante.required' => 'El teléfono es obligatorio.',
            'telefono_restaurante.regex' => 'El teléfono debe tener exactamente 9 números.',
            'web_real_restaurante.required' => 'La página web es obligatoria.',
            'web_real_restaurante.url' => 'La página web debe ser una URL válida (ej. https://...).',
            'estilos.required' => 'Debes seleccionar al menos 1 estilo de cocina.',
            'estilos.min' => 'Debes seleccionar al menos 1 estilo de cocina.',
        ]);

        // actualizar datos del restaurante
        $datosRestaurante = [
            'nombre_restaurante' => $datos['nombre_restaurante'],
            'slug' => Str::slug($datos['nombre_restaurante']),
            'telefono_restaurante' => $datos['telefono_restaurante'] ?? null,
            'precio_restaurante' => $datos['precio_restaurante'] ?? null,
            'descripcion_restaurante' => $datos['descripcion_restaurante'] ?? null,
            'web_real_restaurante' => $datos['web_real_restaurante'] ?? null,
            'id_ciudad' => $datos['id_ciudad'],
        ];

        // solo actualizar valoracion si tiene valor
        if (!empty($datos['valoracion_restaurante'])) {
            $datosRestaurante['valoracion_restaurante'] = $datos['valoracion_restaurante'];
        }

        $restaurante->update($datosRestaurante);

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

        // ===== ENVÍO DE CORREO ELECTRÓNICO =====
        // Enviamos un correo de notificación al administrador
        try {
            // Enviamos el correo con la acción 'editar'
            Mail::to('marcnavarrojocs@gmail.com')->send(
                new NotificacionCrudRestaurante(
                    'editar',                   // Tipo de acción realizada
                    $restaurante->load('ciudad'), // Restaurante actualizado con relación ciudad
                    auth()->user()              // Usuario que realizó la actualización
                )
            );
        } catch (\Exception $e) {
            // Si falla el envío, lo registramos pero continuamos
            Log::error('Error al enviar correo de notificación: ' . $e->getMessage());
        }
        // ===== FIN ENVÍO DE CORREO =====

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante actualizado correctamente.');
    }

    // eliminar un restaurante
    public function eliminar($id)
    {
        $restaurante = Restaurante::findOrFail($id);

        // ===== GUARDAMOS DATOS ANTES DE ELIMINAR =====
        // Necesitamos guardar el nombre antes de eliminar para enviarlo en el correo
        // porque después de delete() ya no tendremos acceso a los datos
        $datosRestaurante = [
            'nombre_restaurante' => $restaurante->nombre_restaurante,
        ];
        // ===== FIN GUARDADO DE DATOS =====

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

        // ===== ENVÍO DE CORREO ELECTRÓNICO =====
        // Enviamos un correo de notificación al administrador
        try {
            // Usamos los datos guardados porque el restaurante ya fue eliminado
            Mail::to('marcnavarrojocs@gmail.com')->send(
                new NotificacionCrudRestaurante(
                    'eliminar',           // Tipo de acción realizada
                    (object)$datosRestaurante, // Convertimos el array a objeto para acceder con ->
                    auth()->user()        // Usuario que realizó la eliminación
                )
            );
        } catch (\Exception $e) {
            // Si falla el envío, lo registramos pero continuamos
            Log::error('Error al enviar correo de notificación: ' . $e->getMessage());
        }
        // ===== FIN ENVÍO DE CORREO =====

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante eliminado correctamente.');
    }
}
