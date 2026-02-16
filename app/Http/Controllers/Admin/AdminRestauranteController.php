<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurante;
use App\Models\Ciudad;
use App\Models\Comunidad;
use App\Models\Pais;
use App\Models\Estilo;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificacionCrudRestaurante;

/**
 * Controlador para gestionar los restaurantes en el panel de administración.
 * Permite listar, crear, editar y eliminar restaurantes.
 */
class AdminRestauranteController extends Controller
{
    /**
     * Muestra la lista de restaurantes con filtros y ordenación.
     */
    public function index(Request $solicitud)
    {
        // Iniciamos la consulta base cargando las relaciones necesarias (ciudad, estilos, imagen)
        // 'with' optimiza la consulta para no hacer una por cada restaurante (N+1 problem)
        $consulta = Restaurante::with(['ciudad.comunidad.pais', 'estilos', 'imagenPrincipal']);

        // --- APLICAR FILTROS ---
        
        // 1. Filtro por nombre (buscador)
        if ($solicitud->filled('busqueda')) {
            $consulta->where('nombre_restaurante', 'like', "%{$solicitud->busqueda}%");
        }

        // 2. Filtro por ciudad
        if ($solicitud->filled('ciudad')) {
            $consulta->where('id_ciudad', $solicitud->ciudad);
        }

        // 2b. Filtro por comunidad (acumulativo)
        if ($solicitud->filled('comunidad')) {
            $consulta->whereHas('ciudad', function ($q) use ($solicitud) {
                $q->where('id_comunidad', $solicitud->comunidad);
            });
        }

        // 2c. Filtro por país (acumulativo)
        if ($solicitud->filled('pais')) {
            $consulta->whereHas('ciudad.comunidad', function ($q) use ($solicitud) {
                $q->where('id_pais', $solicitud->pais);
            });
        }

        // 3. Filtro por estilo de cocina
        if ($solicitud->filled('estilo')) {
            // whereHas busca dentro de una relación (restaurantes que tengan ese estilo)
            $consulta->whereHas('estilos', function ($query) use ($solicitud) {
                $query->where('estilos.id_estilo', $solicitud->estilo);
            });
        }

        // 4. Filtro por valoración mínima
        if ($solicitud->filled('valoracion')) {
            $consulta->where('valoracion_restaurante', '>=', $solicitud->valoracion);
        }

        // --- APLICAR ORDENACIÓN ---
        
        // Obtenemos columna y dirección (por defecto: ordenar por ID ascendente)
        $ordenarPor = $solicitud->get('orden', 'id_restaurante');
        $direccion = $solicitud->get('dir', 'asc');
        
        // Validamos que la columna sea válida para evitar inyecciones o errores
        $columnasPermitidas = ['nombre_restaurante', 'precio_restaurante', 'valoracion_restaurante', 'id_restaurante'];
        if (!in_array($ordenarPor, $columnasPermitidas)) $ordenarPor = 'id_restaurante';
        
        // Aplicamos el orden
        $consulta->orderBy($ordenarPor, $direccion);

        // --- OBTENER DATOS FINALES ---

        // Paginamos los resultados (10 por página) y mantenemos los filtros en la URL (withQueryString)
        $listaRestaurantes = $consulta->paginate(10)->withQueryString();
        
        // Cargamos listas para los desplegables de filtros (dependientes)
        $listaPaises = Pais::orderBy('nombre')->get();

        if ($solicitud->filled('pais')) {
            $listaComunidades = Comunidad::where('id_pais', $solicitud->pais)->orderBy('nombre_comunidad')->get();
        } else {
            $listaComunidades = Comunidad::orderBy('nombre_comunidad')->get();
        }

        if ($solicitud->filled('comunidad')) {
            $listaCiudades = Ciudad::where('id_comunidad', $solicitud->comunidad)->orderBy('nombre_ciudad')->get();
        } elseif ($solicitud->filled('pais')) {
            $listaCiudades = Ciudad::whereHas('comunidad', function ($q) use ($solicitud) {
                $q->where('id_pais', $solicitud->pais);
            })->orderBy('nombre_ciudad')->get();
        } else {
            $listaCiudades = Ciudad::orderBy('nombre_ciudad')->get();
        }

        $listaEstilos = Estilo::orderBy('nombre_estilo')->get();

        // Devolvemos la vista con los datos
        return view('admin.restaurantes.index', [
            'restaurantes' => $listaRestaurantes,
            'paises' => $listaPaises,
            'comunidades' => $listaComunidades,
            'ciudades' => $listaCiudades,
            'estilos' => $listaEstilos
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo restaurante.
     */
    public function crear()
    {
        // Necesitamos paises/comunidades/ciudades y estilos para los selectores del formulario
        $listaPaises = Pais::orderBy('nombre')->get();
        $listaComunidades = Comunidad::orderBy('nombre_comunidad')->get();
        $listaCiudades = Ciudad::orderBy('nombre_ciudad')->get();
        $listaEstilos = Estilo::orderBy('nombre_estilo')->get();

        return view('admin.restaurantes.crear', [
            'paises' => $listaPaises,
            'comunidades' => $listaComunidades,
            'ciudades' => $listaCiudades,
            'estilos' => $listaEstilos
        ]);
    }

    /**
     * Guarda un nuevo restaurante en la base de datos.
     */
    public function guardar(Request $solicitud)
    {
        // 1. Validar los datos del formulario
        $datosValidados = $this->validarFormulario($solicitud);

        // 2. Crear el restaurante en la base de datos
        // Usamos Str::slug para crear una URL amigable (ej: "El Bulli" -> "el-bulli")
        $datosValidados['slug'] = Str::slug($datosValidados['nombre_restaurante']);
        
        $nuevoRestaurante = Restaurante::create($datosValidados);

        // 3. Guardar relaciones (Estilos de cocina)
        if (!empty($datosValidados['estilos'])) {
            $nuevoRestaurante->estilos()->attach($datosValidados['estilos']);
        }

        // 4. Subir y guardar imágenes
        $this->subirImagenes($solicitud, $nuevoRestaurante->id_restaurante);

        // 5. Enviar correo de notificación al administrador
        $this->enviarCorreoNotificacion('crear', $nuevoRestaurante);

        // 6. Redirigir al listado con mensaje de éxito
        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un restaurante.
     */
    public function editar($id)
    {
        $restaurante = Restaurante::with(['ciudad.comunidad.pais', 'estilos', 'imagenes'])->findOrFail($id);
        $listaPaises = Pais::orderBy('nombre')->get();
        $listaComunidades = Comunidad::orderBy('nombre_comunidad')->get();
        $listaCiudades = Ciudad::orderBy('nombre_ciudad')->get();
        $listaEstilos = Estilo::orderBy('nombre_estilo')->get();

        return view('admin.restaurantes.editar', [
            'restaurante' => $restaurante,
            'paises' => $listaPaises,
            'comunidades' => $listaComunidades,
            'ciudades' => $listaCiudades,
            'estilos' => $listaEstilos
        ]);
    }

    /**
     * Actualiza un restaurante existente en la base de datos.
     */
    public function actualizar(Request $solicitud, $id)
    {
        $restaurante = Restaurante::findOrFail($id);

        // 1. Validar datos
        $datosValidados = $this->validarFormulario($solicitud);

        // 2. Actualizar datos del restaurante
        $datosValidados['slug'] = Str::slug($datosValidados['nombre_restaurante']);
        $restaurante->update($datosValidados);

        // 3. Sincronizar estilos (sync elimina los anteriores y guarda los nuevos)
        $restaurante->estilos()->sync($datosValidados['estilos']);

        // 4. Subir nuevas imágenes
        $this->subirImagenes($solicitud, $restaurante->id_restaurante);

        // 5. Eliminar imágenes marcadas para borrar
        if ($solicitud->has('eliminar_imagenes')) {
            $this->eliminarImagenesSeleccionadas($solicitud->eliminar_imagenes);
        }

        // 6. Enviar correo de notificación
        $this->enviarCorreoNotificacion('editar', $restaurante);

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante actualizado correctamente.');
    }

    /**
     * Elimina un restaurante de la base de datos.
     */
    public function eliminar($id)
    {
        $restaurante = Restaurante::findOrFail($id);

        // Guardamos copia de los datos antes de borrar para el correo
        $datosCopia = (object) [
            'nombre_restaurante' => $restaurante->nombre_restaurante,
            'id_restaurante' => $restaurante->id_restaurante,
            'ciudad' => $restaurante->ciudad // Mantenemos relación si está cargada
        ];

        // 1. Eliminar archivos de imagen del disco
        foreach ($restaurante->imagenes as $imagen) {
            if (!str_starts_with($imagen->imagen, 'assets/')) {
                Storage::disk('public')->delete($imagen->imagen);
            }
        }

        // 2. Eliminar relaciones y registros
        $restaurante->estilos()->detach();
        $restaurante->valoraciones()->delete();
        $restaurante->comentarios()->delete();
        $restaurante->guardadoPorUsuarios()->detach();
        $restaurante->imagenes()->delete();
        $restaurante->delete();

        // 3. Enviar correo de notificación
        $this->enviarCorreoNotificacion('eliminar', $datosCopia);

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante eliminado correctamente.');
    }

    // 
    //                      MÉTODOS PRIVADOS DE AYUDA
    // 

    /**
     * Valida los datos del formulario común (crear y editar).
     */
    private function validarFormulario(Request $solicitud)
    {
        return $solicitud->validate([
            'nombre_restaurante' => 'required|string|max:255',
            'telefono_restaurante' => 'required|regex:/^\\d{9}$/',
            'precio_restaurante' => 'nullable|numeric|min:0',
            'descripcion_restaurante' => 'nullable|string',
            'valoracion_restaurante' => 'nullable|numeric|min:0|max:5',
            'web_real_restaurante' => 'required|url|max:255',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'estilos' => 'required|array|min:1',
            'estilos.*' => 'exists:estilos,id_estilo',
            'imagenes.*' => 'nullable|image|max:2048', // Específico para imágenes (2MB máx)
        ], [
            // Mensajes de error personalizados en español
            'nombre_restaurante.required' => 'El nombre es obligatorio.',
            'telefono_restaurante.regex' => 'El teléfono debe tener 9 números.',
            'web_real_restaurante.url' => 'La web debe ser una URL válida.',
            'estilos.required' => 'Debes seleccionar al menos un estilo de cocina.',
        ]);
    }

    /**
     * Sube las imágenes al servidor y crea los registros en la BD.
     */
    private function subirImagenes(Request $solicitud, $idRestaurante)
    {
        if ($solicitud->hasFile('imagenes')) {
            foreach ($solicitud->file('imagenes') as $archivo) {
                // Guarda archivo en 'storage/app/public/restaurantes'
                $ruta = $archivo->store('restaurantes', 'public');
                
                Imagen::create([
                    'imagen' => $ruta,
                    'id_restaurante' => $idRestaurante,
                ]);
            }
        }
    }

    /**
     * Elimina imágenes seleccionadas por el usuario.
     */
    private function eliminarImagenesSeleccionadas($idsImagenes)
    {
        foreach ($idsImagenes as $id) {
            $imagen = Imagen::find($id);
            if ($imagen) {
                // Borrar archivo físico si no es un asset de ejemplo
                if (!str_starts_with($imagen->imagen, 'assets/')) {
                    Storage::disk('public')->delete($imagen->imagen);
                }
                $imagen->delete();
            }
        }
    }

    /**
     * Gestiona el envío de correos de forma centralizada.
     * Captura errores para que no falle la web si falla el correo.
     */
    private function enviarCorreoNotificacion($accion, $datosRestaurante)
    {
        // Dirección donde se enviarán las alertas
        $destinatario = 'marcnavarrojocs@gmail.com';
        
        // Usuario que realiza la acción (si hay login)
        $usuario = auth()->user();

        try {
            Mail::to($destinatario)->send(
                new NotificacionCrudRestaurante($accion, $datosRestaurante, $usuario)
            );
        } catch (\Exception $e) {
            // Logueamos el error pero no detenemos la ejecución
            Log::error("Error enviando correo ($accion): " . $e->getMessage());
        }
    }
}
