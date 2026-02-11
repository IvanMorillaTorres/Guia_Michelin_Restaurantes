@extends('layouts.app')

@section('titulo', 'Crear cuenta - Guía MICHELIN')

@section('contenido')
<div class="pagina-login">
    <div class="container">
        <div class="caja-login">
            <h2>Crear cuenta</h2>
            <p class="subtitulo-login">Regístrate en la Guía MICHELIN</p>

            @if($errors->any())
                <div class="alerta-error">
                    @foreach($errors->all() as $error)
                        <p><i class="bi bi-exclamation-circle"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('registro') }}">
                @csrf
                <div class="registro-fila">
                    <div class="campo-formulario">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus placeholder="Tu nombre">
                    </div>
                    <div class="campo-formulario">
                        <label for="apellido1">Primer apellido *</label>
                        <input type="text" id="apellido1" name="apellido1" value="{{ old('apellido1') }}" required placeholder="Primer apellido">
                    </div>
                </div>
                <div class="registro-fila">
                    <div class="campo-formulario">
                        <label for="apellido2">Segundo apellido</label>
                        <input type="text" id="apellido2" name="apellido2" value="{{ old('apellido2') }}" placeholder="Segundo apellido">
                    </div>
                    <div class="campo-formulario">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="600 123 456">
                    </div>
                </div>
                <div class="campo-formulario">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="tu@email.com">
                </div>
                <div class="registro-fila">
                    <div class="campo-formulario">
                        <label for="password">Contraseña *</label>
                        <input type="password" id="password" name="password" required placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="campo-formulario">
                        <label for="password_confirmation">Confirmar contraseña *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repite la contraseña">
                    </div>
                </div>
                <button type="submit" class="btn-login">
                    <i class="bi bi-person-plus"></i> Crear cuenta
                </button>
            </form>

            <div class="enlace-auth">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </div>
        </div>
    </div>
</div>
@endsection
