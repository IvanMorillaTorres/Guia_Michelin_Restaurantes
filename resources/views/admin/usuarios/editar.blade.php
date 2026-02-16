@extends('layouts.admin')

@section('titulo', 'Editar Usuario: ' . $usuario->nombre . ' - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Editar Usuario</h1>
    <a href="{{ route('admin.usuarios.index') }}" class="btn-admin-volver">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

@if($errors->any())
    <div class="alerta-error">
        @foreach($errors->all() as $error)
            <p><i class="bi bi-exclamation-circle"></i> {{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.usuarios.actualizar', $usuario->id_users) }}" class="admin-formulario">
    @csrf
    @method('PUT')

    <div class="admin-grid-form">
        <!-- Nombre -->
        <div class="campo-formulario">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
        </div>

        <!-- Apellido 1 -->
        <div class="campo-formulario">
            <label for="apellido1">Primer apellido *</label>
            <input type="text" id="apellido1" name="apellido1" value="{{ old('apellido1', $usuario->apellido1) }}" required>
        </div>

        <!-- Apellido 2 -->
        <div class="campo-formulario">
            <label for="apellido2">Segundo apellido</label>
            <input type="text" id="apellido2" name="apellido2" value="{{ old('apellido2', $usuario->apellido2) }}">
        </div>

        <!-- Email -->
        <div class="campo-formulario">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <!-- Contraseña -->
        <div class="campo-formulario">
            <label for="password">Contraseña (dejar vacío para mantener la actual)</label>
            <input type="password" id="password" name="password">
            <small class="texto-gris">Mínimo 6 caracteres. Dejar vacío si no deseas cambiarla.</small>
        </div>

        <!-- Teléfono -->
        <div class="campo-formulario">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
        </div>

        <!-- Fecha de nacimiento -->
        <div class="campo-formulario">
            <label for="nacimiento">Fecha de nacimiento</label>
            <input type="date" id="nacimiento" name="nacimiento" value="{{ old('nacimiento', $usuario->nacimiento?->format('Y-m-d')) }}">
        </div>

        <!-- Rol -->
        <div class="campo-formulario">
            <label for="id_rol">Rol *</label>
            <select id="id_rol" name="id_rol" required>
                <option value="">Seleccionar rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id_rol }}" {{ old('id_rol', $usuario->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Estado -->
        <div class="campo-formulario">
            <label for="estado">Estado *</label>
            <select id="estado" name="estado" required>
                <option value="">Seleccionar estado</option>
                <option value="activo" {{ old('estado', $usuario->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('estado', $usuario->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
    </div>

    <div class="admin-form-acciones">
        <button type="submit" class="btn-admin-guardar">
            <i class="bi bi-check-lg"></i> Actualizar usuario
        </button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn-admin-cancelar">Cancelar</a>
    </div>
</form>
@endsection
