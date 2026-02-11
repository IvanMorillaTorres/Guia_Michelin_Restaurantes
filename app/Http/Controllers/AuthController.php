<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // intentar autenticar con email y password
        if (Auth::attempt(['email' => $credenciales['email'], 'password' => $credenciales['password']])) {
            $request->session()->regenerate();

            $usuario = Auth::user();

            // si es admin (id_rol = 1), redirigir al panel admin
            if ($usuario->id_rol == 1) {
                return redirect()->route('admin.restaurantes.index');
            }

            return redirect()->route('restaurantes.index');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    // cerrar sesion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('restaurantes.index');
    }
}
