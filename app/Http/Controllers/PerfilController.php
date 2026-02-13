<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function actualizarDatos(Request $request)
    {
        $usuario = Auth::user();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido1' => ['required', 'string', 'max:255'],
            'apellido2' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id_users, 'id_users'),
            ],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s()\-]*$/'],
            'nacimiento' => ['nullable', 'date', 'before:today'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido1.required' => 'El primer apellido es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios y símbolos + ( ) -.',
            'nacimiento.before' => 'La fecha de nacimiento no puede ser futura.',
        ]);

        $usuario->fill($datos);
        $usuario->save();

        return back()->with('perfil_ok', 'Datos actualizados correctamente.');
    }

    public function actualizarPassword(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'password_actual' => ['required'],
            'password_nueva' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password_actual.required' => 'La contraseña actual es obligatoria.',
            'password_nueva.required' => 'La nueva contraseña es obligatoria.',
            'password_nueva.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'password_nueva.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if (!Hash::check($request->password_actual, $usuario->password_hash)) {
            return back()->withErrors([
                'password_actual' => 'La contraseña actual no es correcta.',
            ]);
        }

        $usuario->password = $request->password_nueva;
        $usuario->save();

        return back()->with('password_ok', 'Contraseña actualizada correctamente.');
    }
}
