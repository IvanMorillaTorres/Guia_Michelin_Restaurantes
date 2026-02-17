@extends('layouts.app')

@section('titulo', 'Iniciar sesión - Guía MICHELIN')

@section('contenido')
<div class="pagina-login">
    <div class="container">
        <div class="caja-login">
            <h2>Iniciar sesión</h2>
            <p class="subtitulo-login">Accede a tu cuenta de la Guía MICHELIN</p>

            @if(session('registro_exitoso'))
                <div class="alerta-exito">
                    <p><i class="bi bi-check-circle"></i> {{ session('registro_exitoso') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="alerta-error">
                    @foreach($errors->all() as $error)
                        <p><i class="bi bi-exclamation-circle"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="campo-formulario">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus placeholder="tu@email.com">
                </div>
                <div class="campo-formulario">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                </button>
            </form>

            <div class="enlace-auth">
                ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate aquí</a>
            </div>
        </div>
    </div>
</div>
@endsection
