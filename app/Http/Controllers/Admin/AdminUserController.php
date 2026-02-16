<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // listado de usuarios en el panel admin
    public function index(Request $request)
    {
        $consulta = User::with('rol');

        // búsqueda por nombre o email
        if ($request->filled('busqueda')) {
            $consulta->where(function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->busqueda}%")
                  ->orWhere('apellido1', 'like', "%{$request->busqueda}%")
                  ->orWhere('email', 'like', "%{$request->busqueda}%");
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

        // ordenación
        $orden = $request->get('orden', 'id_users');
        $dir = $request->get('dir', 'asc');
        $columnasPermitidas = ['nombre', 'email', 'id_rol', 'estado', 'id_users'];
        if (!in_array($orden, $columnasPermitidas)) $orden = 'id_users';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'asc';

        $consulta->orderBy($orden, $dir);

        $usuarios = $consulta->paginate(10)->withQueryString();
        $roles = Rol::orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    // formulario para crear un nuevo usuario
    public function crear()
    {
        $roles = Rol::orderBy('nombre')->get();

        return view('admin.usuarios.crear', compact('roles'));
    }

    // guardar el nuevo usuario en la base de datos
    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido1' => 'required|string|max:100',
            'apellido2' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
            'nacimiento' => 'nullable|date',
            'id_rol' => 'required|exists:roles,id_rol',
            'estado' => 'required|in:activo,inactivo',
        ]);

        // crear el usuario
        $usuario = new User();
        $usuario->nombre = $datos['nombre'];
        $usuario->apellido1 = $datos['apellido1'];
        $usuario->apellido2 = $datos['apellido2'] ?? null;
        $usuario->email = $datos['email'];
        $usuario->password = $datos['password']; // esto triggereará el mutator
        $usuario->telefono = $datos['telefono'] ?? null;
        $usuario->nacimiento = $datos['nacimiento'] ?? null;
        $usuario->id_rol = $datos['id_rol'];
        $usuario->estado = $datos['estado'];
        $usuario->save();

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario creado correctamente.');
    }

    // formulario para editar un usuario existente
    public function editar($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::orderBy('nombre')->get();

        return view('admin.usuarios.editar', compact('usuario', 'roles'));
    }

    // actualizar el usuario en la base de datos
    public function actualizar(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido1' => 'required|string|max:100',
            'apellido2' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',id_users',
            'password' => 'nullable|string|min:6',
            'telefono' => 'nullable|string|max:20',
            'nacimiento' => 'nullable|date',
            'id_rol' => 'required|exists:roles,id_rol',
            'estado' => 'required|in:activo,inactivo',
        ]);

        // actualizar datos del usuario
        $usuario->nombre = $datos['nombre'];
        $usuario->apellido1 = $datos['apellido1'];
        $usuario->apellido2 = $datos['apellido2'] ?? null;
        $usuario->email = $datos['email'];
        $usuario->telefono = $datos['telefono'] ?? null;
        $usuario->nacimiento = $datos['nacimiento'] ?? null;
        $usuario->id_rol = $datos['id_rol'];
        $usuario->estado = $datos['estado'];

        // actualizar contraseña solo si se proporciona
        if (!empty($datos['password'])) {
            $usuario->password = $datos['password'];
        }

        $usuario->save();

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario actualizado correctamente.');
    }

    // eliminar un usuario
    public function eliminar($id)
    {
        $usuario = User::findOrFail($id);
        
        // prevenir que se elimine el usuario admin actual
        if ($usuario->id_users == auth()->user()->id_users) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario eliminado correctamente.');
    }
}
