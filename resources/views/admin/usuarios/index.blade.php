@extends('layouts.admin')

@section('titulo', 'Gestionar Usuarios - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Gestionar Usuarios</h1>
    <a href="{{ route('admin.usuarios.crear') }}" class="btn-admin-nuevo">
        <i class="bi bi-plus-lg"></i> Nuevo usuario
    </a>
</div>

<!-- Filtros -->
<form method="GET" action="{{ route('admin.usuarios.index') }}" class="admin-filtros">
    <div class="admin-filtros-fila">
        <div class="admin-filtro-campo">
            <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Buscar por nombre o email..." class="admin-campo-busqueda">
        </div>
        <div class="admin-filtro-campo">
            <select name="rol" class="admin-campo-select">
                <option value="">Todos los roles</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id_rol }}" {{ request('rol') == $rol->id_rol ? 'selected' : '' }}>
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="admin-filtro-campo">
            <select name="estado" class="admin-campo-select">
                <option value="">Todos los estados</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <div class="admin-filtro-acciones">
            <button type="submit" class="btn-admin-buscar"><i class="bi bi-search"></i> Filtrar</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn-admin-limpiar"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<!-- Tabla de usuarios -->
<div class="admin-tabla-responsive">
    <table class="admin-tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                <tr>
                    <td data-label="Nombre">
                        <strong>{{ $usuario->nombre }} {{ $usuario->apellido1 }}</strong>
                        @if($usuario->apellido2)
                            {{ $usuario->apellido2 }}
                        @endif
                    </td>
                    <td data-label="Email">{{ $usuario->email }}</td>
                    <td data-label="Teléfono">{{ $usuario->telefono ?? '-' }}</td>
                    <td data-label="Rol">
                        <span class="admin-etiqueta">{{ $usuario->rol->nombre }}</span>
                    </td>
                    <td data-label="Estado">
                        <span class="admin-estado-{{ $usuario->estado }}">
                            {{ ucfirst($usuario->estado) }}
                        </span>
                    </td>
                    <td data-label="Acciones" class="admin-acciones">
                        <a href="{{ route('admin.usuarios.editar', $usuario->id_users) }}" class="btn-admin-editar" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($usuario->id_users != auth()->id())
                            <form method="POST" action="{{ route('admin.usuarios.eliminar', $usuario->id_users) }}" style="display:inline;" onsubmit="confirmarEliminacion(event, '¿Estás seguro de que deseas eliminar este usuario? Se eliminarán también todos sus datos asociados.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-admin-eliminar" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-vacio">No se encontraron usuarios con esos filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
@if($usuarios->hasPages())
<div class="admin-paginacion">
    <div class="admin-paginacion-enlaces">
        {{-- Anterior --}}
        @if($usuarios->onFirstPage())
            <span class="admin-pagina disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <a href="{{ $usuarios->previousPageUrl() }}" class="admin-pagina"><i class="bi bi-chevron-left"></i></a>
        @endif

        {{-- Numeros de pagina --}}
        @php
            $paginaActual = $usuarios->currentPage();
            $ultimaPagina = $usuarios->lastPage();
            $rango = 2;
            $inicio = max(1, $paginaActual - $rango);
            $fin = min($ultimaPagina, $paginaActual + $rango);
        @endphp

        @if($inicio > 1)
            <a href="{{ $usuarios->url(1) }}" class="admin-pagina">1</a>
            @if($inicio > 2)
                <span class="admin-pagina puntos">…</span>
            @endif
        @endif

        @for($i = $inicio; $i <= $fin; $i++)
            @if($i == $paginaActual)
                <span class="admin-pagina activa">{{ $i }}</span>
            @else
                <a href="{{ $usuarios->url($i) }}" class="admin-pagina">{{ $i }}</a>
            @endif
        @endfor

        @if($fin < $ultimaPagina)
            @if($fin < $ultimaPagina - 1)
                <span class="admin-pagina puntos">…</span>
            @endif
            <a href="{{ $usuarios->url($ultimaPagina) }}" class="admin-pagina">{{ $ultimaPagina }}</a>
        @endif

        {{-- Siguiente --}}
        @if($usuarios->hasMorePages())
            <a href="{{ $usuarios->nextPageUrl() }}" class="admin-pagina"><i class="bi bi-chevron-right"></i></a>
        @else
            <span class="admin-pagina disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
    <div class="admin-paginacion-info">
        Página {{ $usuarios->currentPage() }} de {{ $usuarios->lastPage() }}
    </div>
</div>
@endif
@endsection
