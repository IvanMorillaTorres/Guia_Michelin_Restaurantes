@extends('layouts.admin')

@section('titulo', 'Gestionar Restaurantes - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Gestionar Restaurantes</h1>
    <a href="{{ route('admin.restaurantes.crear') }}" class="btn-admin-nuevo">
        <i class="bi bi-plus-lg"></i> Nuevo restaurante
    </a>
</div>

<!-- Buscador rapido -->
<form method="GET" action="{{ route('admin.restaurantes.index') }}" class="admin-buscador">
    <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Buscar por nombre..." class="admin-campo-busqueda">
    <button type="submit" class="btn-admin-buscar"><i class="bi bi-search"></i></button>
</form>

<!-- Tabla de restaurantes -->
<div class="admin-tabla-contenedor">
    <table class="admin-tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Ciudad</th>
                <th>Precio</th>
                <th>Valoración</th>
                <th>Estilos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($restaurantes as $restaurante)
                <tr>
                    <td>{{ $restaurante->id_restaurante }}</td>
                    <td>
                        @if($restaurante->imagenPrincipal)
                            <img src="{{ $restaurante->imagenPrincipal->url }}" alt="" class="admin-miniatura">
                        @else
                            <span class="admin-sin-imagen">🍽️</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $restaurante->nombre_restaurante }}</strong>
                        <br><small class="texto-gris">{{ $restaurante->slug }}</small>
                    </td>
                    <td>{{ $restaurante->ciudad->nombre_ciudad ?? '—' }}</td>
                    <td>{{ $restaurante->precio_restaurante ? number_format($restaurante->precio_restaurante, 0) . '€' : '—' }}</td>
                    <td>
                        @if($restaurante->valoracion_restaurante)
                            <span class="admin-valoracion">
                                <i class="bi bi-star-fill"></i> {{ number_format($restaurante->valoracion_restaurante, 1) }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @foreach($restaurante->estilos as $estilo)
                            <span class="admin-etiqueta">{{ $estilo->nombre_estilo }}</span>
                        @endforeach
                    </td>
                    <td class="admin-acciones">
                        <a href="{{ route('admin.restaurantes.editar', $restaurante->id_restaurante) }}" class="btn-admin-editar" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.restaurantes.eliminar', $restaurante->id_restaurante) }}" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres eliminar este restaurante?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin-eliminar" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="admin-vacio">No hay restaurantes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginacion -->
<div class="paginacion">
    {{ $restaurantes->links() }}
</div>
@endsection
