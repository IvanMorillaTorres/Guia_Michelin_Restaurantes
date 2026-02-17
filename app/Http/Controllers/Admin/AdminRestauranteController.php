<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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



class AdminRestauranteController extends Controller
{
    // listado de restaurantes con filtros y ordenación
    public function index(Request $solicitud)
    {
        // cargamos las relaciones para no hacer mil consultas (evita el N+1)
        $consulta = Restaurante::with(['ciudad.comunidad.pais', 'estilos', 'imagenPrincipal']);

        // filtro por nombre (solo si han buscado algo)
        if ($solicitud->filled('busqueda')) {
            $consulta->where('nombre_restaurante', 'like', "%{$solicitud->busqueda}%");
        }

        // filtro por ciudad
        if ($solicitud->filled('ciudad')) {
            $consulta->where('id_ciudad', $solicitud->ciudad);
        }

        // filtro por comunidad
        if ($solicitud->filled('comunidad')) {
            $consulta->whereHas('ciudad', function ($q) use ($solicitud) {
                $q->where('id_comunidad', $solicitud->comunidad);
            });
        }

        // filtro por pais
        if ($solicitud->filled('pais')) {
            $consulta->whereHas('ciudad.comunidad', function ($q) use ($solicitud) {
                $q->where('id_pais', $solicitud->pais);
            });
        }

        // filtro por estilo de cocina
        if ($solicitud->filled('estilo')) {
            // whereHas busca en la relacion
            $consulta->whereHas('estilos', function ($query) use ($solicitud) {
                $query->where('estilos.id_estilo', $solicitud->estilo);
            });
        }

        // filtro por valoracion minima
        if ($solicitud->filled('valoracion')) {
            $consulta->where('valoracion_restaurante', '>=', $solicitud->valoracion);
        }

        // ordenacion
        // columna y direccion, por defecto ordena por ID
        $ordenarPor = $solicitud->get('orden', 'id_restaurante');
        $direccion = $solicitud->get('dir', 'asc');
        
        // validamos la columna pa evitar inyecciones
        $columnasPermitidas = ['nombre_restaurante', 'precio_restaurante', 'valoracion_restaurante', 'id_restaurante'];
        if (!in_array($ordenarPor, $columnasPermitidas)) $ordenarPor = 'id_restaurante';
        
        // aplicamos el orden
        $consulta->orderBy($ordenarPor, $direccion);

        // datos finales

        // paginamos de 10 en 10 y conservamos los filtros en los enlaces
        $listaRestaurantes = $consulta->paginate(10)->withQueryString();
        
        // listas para los selects de filtros
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

        // devolvemos la vista
        return view('admin.restaurantes.index', [
            'restaurantes' => $listaRestaurantes,
            'paises' => $listaPaises,
            'comunidades' => $listaComunidades,
            'ciudades' => $listaCiudades,
            'estilos' => $listaEstilos
        ]);
    }

    // formulario para crear restaurante
    public function crear()
    {
        // sacamos los datos para los selects del formulario
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

    // guardar restaurante nuevo en la BD
    public function guardar(Request $solicitud)
    {
        // validamos los datos
        $datosValidados = $this->validarFormulario($solicitud);

        // generamos el slug para la URL amigable (ej: "La Pepita" -> "la-pepita")
        $datosValidados['slug'] = Str::slug($datosValidados['nombre_restaurante']);
        
        $nuevoRestaurante = Restaurante::create($datosValidados);

        // guardamos los estilos
        if (!empty($datosValidados['estilos'])) {
            $nuevoRestaurante->estilos()->attach($datosValidados['estilos']); // los asociamos en la tabla pivot
        }

        // subimos las imagenes
        $this->subirImagenes($solicitud, $nuevoRestaurante->id_restaurante);

        // mandamos correo al admin
        $this->enviarCorreoNotificacion('crear', $nuevoRestaurante);

        // redirigimos al listado con mensaje de exito
        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante creado correctamente.');
    }

    // formulario para editar restaurante
    public function editar($id)
    {
        // buscamos el restaurante con sus relaciones, si no existe salta 404
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

    // actualizar restaurante en la BD
    public function actualizar(Request $solicitud, $id)
    {
        $restaurante = Restaurante::findOrFail($id);

        // validamos
        $datosValidados = $this->validarFormulario($solicitud);

        // actualizamos los datos
        $datosValidados['slug'] = Str::slug($datosValidados['nombre_restaurante']);
        $restaurante->update($datosValidados);

        // actualizamos los estilos: quita los viejos y mete los nuevos
        $restaurante->estilos()->sync($datosValidados['estilos']);

        // subimos imagenes nuevas si hay
        $this->subirImagenes($solicitud, $restaurante->id_restaurante);

        // si el admin ha marcado imagenes para borrar las eliminamos
        if ($solicitud->has('eliminar_imagenes')) {
            $this->eliminarImagenesSeleccionadas($solicitud->eliminar_imagenes);
        }

        // correo de notificacion
        $this->enviarCorreoNotificacion('editar', $restaurante);

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante actualizado correctamente.');
    }

    // eliminar restaurante
    public function eliminar($id)
    {
        $restaurante = Restaurante::findOrFail($id);

        // copiamos los datos antes de borrar pa mandar el correo
        $datosCopia = (object) [
            'nombre_restaurante' => $restaurante->nombre_restaurante,
            'id_restaurante' => $restaurante->id_restaurante,
            'ciudad' => $restaurante->ciudad
        ];

        // borramos las imagenes del disco
        foreach ($restaurante->imagenes as $imagen) {
            if (!str_starts_with($imagen->imagen, 'assets/')) {
                // borramos el archivo del disco
                Storage::disk('public')->delete($imagen->imagen);
            }
        }

        // borramos todo lo relacionado dentro de una transaccion (si algo falla se deshace todo)
        DB::transaction(function () use ($restaurante) {
            $restaurante->estilos()->detach(); // quitamos las relaciones de la tabla pivot
            $restaurante->valoraciones()->delete();
            $restaurante->comentarios()->delete();
            $restaurante->guardadoPorUsuarios()->detach();
            $restaurante->imagenes()->delete();
            $restaurante->delete();
        });

        // mandamos correo
        $this->enviarCorreoNotificacion('eliminar', $datosCopia);

        return redirect()->route('admin.restaurantes.index')
            ->with('exito', 'Restaurante eliminado correctamente.');
    }

    // metodos auxiliares


    // validacion comun para crear y editar
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
            'imagenes.*' => 'nullable|image|max:2048', // max 2MB por imagen
        ], [
            // mensajes en español
            'nombre_restaurante.required' => 'El nombre es obligatorio.',
            'telefono_restaurante.regex' => 'El teléfono debe tener 9 números.',
            'web_real_restaurante.url' => 'La web debe ser una URL válida.',
            'estilos.required' => 'Debes seleccionar al menos un estilo de cocina.',
        ]);
    }

    // sube imagenes y las guarda en la BD
    private function subirImagenes(Request $solicitud, $idRestaurante)
    {
        if ($solicitud->hasFile('imagenes')) {
            foreach ($solicitud->file('imagenes') as $archivo) {
                // guardamos el archivo en storage/public/restaurantes y nos devuelve la ruta
                $ruta = $archivo->store('restaurantes', 'public');
                
                Imagen::create([
                    'imagen' => $ruta,
                    'id_restaurante' => $idRestaurante,
                ]);
            }
        }
    }

    // borra las imagenes que el admin ha marcado para eliminar
    private function eliminarImagenesSeleccionadas($idsImagenes)
    {
        foreach ($idsImagenes as $id) {
            $imagen = Imagen::find($id);
            if ($imagen) {
                // si no es un asset de ejemplo, borramos el archivo
                if (!str_starts_with($imagen->imagen, 'assets/')) {
                    Storage::disk('public')->delete($imagen->imagen);
                }
                $imagen->delete();
            }
        }
    }

    // manda correos, si falla no peta la app
    private function enviarCorreoNotificacion($accion, $datosRestaurante)
    {
        // mail del admin que recibe las alertas
        $destinatario = 'marcnavarrojocs@gmail.com';
        
        // el usuario que hizo la accion
        $usuario = auth()->user();

        try {
            // mandamos el correo
            Mail::to($destinatario)->send(
                new NotificacionCrudRestaurante($accion, $datosRestaurante, $usuario)
            );
        } catch (\Exception $e) {
            // si peta el correo al menos lo apuntamos en el log
            Log::error("Error enviando correo ($accion): " . $e->getMessage());
        }
    }
}
