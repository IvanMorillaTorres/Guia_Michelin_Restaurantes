<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // mostrar formulario de login
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // procesar el login
    public function login(Request $request)
    {
        // validamos que el email y la contraseña vengan bien
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // intentamos autenticar con el email y password
        if (Auth::attempt(['email' => $credenciales['email'], 'password' => $credenciales['password']])) {
            $request->session()->regenerate(); // renovamos la sesion por seguridad

            $usuario = Auth::user();

            // si es admin (id_rol = 1), redirigir al panel admin
            if ($usuario->id_rol == 1) {
                return redirect()->route('admin.restaurantes.index');
            }

            return redirect()->route('restaurantes.index');
        }

        // si las credenciales son incorrectas volvemos atras con el error
        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email'); // solo mantenemos el email en el formulario
    }

    // mostrar formulario de registro
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    // procesar el registro
    public function registro(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido1.required' => 'El primer apellido es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        User::create([
            'nombre' => $datos['nombre'],
            'apellido1' => $datos['apellido1'],
            'apellido2' => $datos['apellido2'] ?? null,
            'email' => $datos['email'],
            'password_hash' => Hash::make($datos['password']), // hasheamos la contraseña
            'telefono' => $datos['telefono'] ?? null,
            'estado' => 'activo',
            'id_rol' => 3, // rol Usuario (cliente)
        ]);

        // mandamos un mensaje de exito (se muestra una sola vez)
        return redirect()->route('login')->with('registro_exitoso', '¡Cuenta creada con éxito! Ya puedes iniciar sesión.');
    }

    // cerrar sesion
    public function logout(Request $request)
    {
        Auth::logout(); // cerramos la sesion
        $request->session()->invalidate(); // invalidamos toda la sesion
        $request->session()->regenerateToken(); // generamos un nuevo token CSRF

        return redirect()->route('restaurantes.index');
    }
}
