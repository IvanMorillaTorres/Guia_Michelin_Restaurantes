<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // listado de usuarios en el panel admin
    public function index(Request $request)
    {
        $consulta = User::with('rol'); // cargamos la relacion con el rol

        // busqueda por nombre o email (solo si han buscado algo)
        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('apellido1', 'like', "%{$busqueda}%")
                  ->orWhere('apellido2', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%");
            });
        }

        // filtro por rol
        if ($request->filled('rol')) {
            $consulta->where('id_rol', $request->rol);
        }

        // filtro por estado
        if ($request->filled('estado')) {
            $consulta->where('estado', $request->estado);
        }

        // paginamos de 10 en 10 y mantenemos los filtros en los enlaces
        $usuarios = $consulta->orderBy('id_users', 'desc')->paginate(10)->withQueryString();
        $roles = Rol::orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    // formulario para crear un usuario nuevo
    public function crear()
    {
        $roles = Rol::orderBy('nombre')->get();
        return view('admin.usuarios.crear', compact('roles'));
    }

    // guardar el usuario nuevo en la base de datos
    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre'     => 'required|string|max:255',
            'apellido1'  => 'required|string|max:255',
            'apellido2'  => 'nullable|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'password'   => 'required|string|min:6',
            'telefono'   => 'nullable|string|max:20',
            'nacimiento' => 'nullable|date',
            'id_rol'     => 'required|exists:roles,id_rol',
            'estado'     => 'required|in:activo,inactivo',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido1.required' => 'El primer apellido es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.unique'       => 'Ya existe un usuario con ese email.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'id_rol.required'    => 'Debes seleccionar un rol.',
            'estado.required'    => 'Debes seleccionar un estado.',
        ]);

        User::create([
            'nombre'     => $datos['nombre'],
            'apellido1'  => $datos['apellido1'],
            'apellido2'  => $datos['apellido2'] ?? null,
            'email'      => $datos['email'],
            'password_hash' => Hash::make($datos['password']), // hasheamos la contraseña
            'telefono'   => $datos['telefono'] ?? null,
            'nacimiento' => $datos['nacimiento'] ?? null,
            'id_rol'     => $datos['id_rol'],
            'estado'     => $datos['estado'],
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario creado correctamente.');
    }

    // formulario para editar un usuario existente
    public function editar($id)
    {
        $usuario = User::findOrFail($id); // buscamos el usuario, si no existe salta 404
        $roles = Rol::orderBy('nombre')->get();

        return view('admin.usuarios.editar', compact('usuario', 'roles'));
    }

    // actualizar el usuario en la base de datos
    public function actualizar(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $datos = $request->validate([
            'nombre'     => 'required|string|max:255',
            'apellido1'  => 'required|string|max:255',
            'apellido2'  => 'nullable|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $usuario->id_users . ',id_users', // email unico, pero sin contar el del propio usuario
            'password'   => 'nullable|string|min:6',
            'telefono'   => 'nullable|string|max:20',
            'nacimiento' => 'nullable|date',
            'id_rol'     => 'required|exists:roles,id_rol',
            'estado'     => 'required|in:activo,inactivo',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido1.required' => 'El primer apellido es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.unique'       => 'Ya existe un usuario con ese email.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'id_rol.required'    => 'Debes seleccionar un rol.',
            'estado.required'    => 'Debes seleccionar un estado.',
        ]);

        $datosActualizar = [
            'nombre'     => $datos['nombre'],
            'apellido1'  => $datos['apellido1'],
            'apellido2'  => $datos['apellido2'] ?? null,
            'email'      => $datos['email'],
            'telefono'   => $datos['telefono'] ?? null,
            'nacimiento' => $datos['nacimiento'] ?? null,
            'id_rol'     => $datos['id_rol'],
            'estado'     => $datos['estado'],
        ];

        // solo actualizar contraseña si se proporcionó una nueva
        if (!empty($datos['password'])) {
            $datosActualizar['password_hash'] = Hash::make($datos['password']);
        }

        $usuario->update($datosActualizar); // actualizamos los datos en la BD

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario actualizado correctamente.');
    }

    // eliminar un usuario
    public function eliminar($id)
    {
        $usuario = User::findOrFail($id);

        // no permitir que el admin se elimine a sí mismo
        if ($usuario->id_users == auth()->id()) { // miramos que no se este eliminando a si mismo
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // eliminar datos relacionados manualmente (transacción)
        // eliminamos todo lo relacionado en una transaccion (si algo falla se deshace todo)
        DB::transaction(function () use ($usuario) {
            $usuario->valoraciones()->delete(); // borramos sus valoraciones
            $usuario->comentarios()->delete();
            $usuario->restaurantesGuardados()->detach(); // quitamos sus restaurantes guardados
            $usuario->delete();
        });

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario eliminado correctamente.');
    }
}