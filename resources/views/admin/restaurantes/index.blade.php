@extends('layouts.admin')

@section('titulo', 'Gestionar Restaurantes - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Gestionar Restaurantes</h1>
    <a href="{{ route('admin.restaurantes.crear') }}" class="btn-admin-nuevo">
        <i class="bi bi-plus-lg"></i> Nuevo restaurante
    </a>
</div>

<!-- Filtros -->
<form method="GET" action="{{ route('admin.restaurantes.index') }}" class="admin-filtros">
    <div class="admin-filtros-fila">
        <div class="admin-filtro-campo">
            <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Buscar por nombre..." class="admin-campo-busqueda">
        </div>
        <div class="admin-filtro-campo">
            <select name="ciudad" class="admin-campo-select">
                <option value="">Todas las ciudades</option>
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" {{ request('ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                        {{ $ciudad->nombre_ciudad }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="admin-filtro-campo">
            <select name="estilo" class="admin-campo-select">
                <option value="">Todos los estilos</option>
                @foreach($estilos as $estilo)
                    <option value="{{ $estilo->id_estilo }}" {{ request('estilo') == $estilo->id_estilo ? 'selected' : '' }}>
                        {{ $estilo->nombre_estilo }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="admin-filtro-campo">
            <select name="valoracion" class="admin-campo-select">
                <option value="">Cualquier valoración</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ request('valoracion') == $i ? 'selected' : '' }}>
                        ≥ {{ $i }} ⭐
                    </option>
                @endfor
            </select>
        </div>
        <div class="admin-filtro-acciones">
            <button type="submit" class="btn-admin-buscar"><i class="bi bi-search"></i> Filtrar</button>
            <a href="{{ route('admin.restaurantes.index') }}" class="btn-admin-limpiar"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<!-- Info resultados -->
<div class="admin-info-resultados">
    Mostrando {{ $restaurantes->firstItem() ?? 0 }}–{{ $restaurantes->lastItem() ?? 0 }} de {{ $restaurantes->total() }} restaurantes
</div>

<!-- Tabla de restaurantes -->
<div class="admin-tabla-contenedor">
    <table class="admin-tabla">
        <thead>
            <tr>
                <th>
                    @php $esOrden = request()->has('orden') && request('orden') == 'id_restaurante'; @endphp
                    <a href="{{ route('admin.restaurantes.index', array_merge(request()->except('orden','dir'), ['orden' => 'id_restaurante', 'dir' => $esOrden && request('dir','asc') == 'asc' ? 'desc' : 'asc'])) }}" class="admin-th-link {{ $esOrden ? 'activo' : '' }}">
                        ID {!! $esOrden ? (request('dir','asc') == 'asc' ? '▲' : '▼') : '' !!}
                    </a>
                </th>
                <th>Imagen</th>
                <th>
                    @php $esOrden = request('orden') == 'nombre_restaurante'; @endphp
                    <a href="{{ route('admin.restaurantes.index', array_merge(request()->except('orden','dir'), ['orden' => 'nombre_restaurante', 'dir' => $esOrden && request('dir','asc') == 'asc' ? 'desc' : 'asc'])) }}" class="admin-th-link {{ $esOrden ? 'activo' : '' }}">
                        Nombre {!! $esOrden ? (request('dir','asc') == 'asc' ? '▲' : '▼') : '' !!}
                    </a>
                </th>
                <th>Ciudad</th>
                <th>
                    @php $esOrden = request('orden') == 'precio_restaurante'; @endphp
                    <a href="{{ route('admin.restaurantes.index', array_merge(request()->except('orden','dir'), ['orden' => 'precio_restaurante', 'dir' => $esOrden && request('dir','asc') == 'asc' ? 'desc' : 'asc'])) }}" class="admin-th-link {{ $esOrden ? 'activo' : '' }}">
                        Precio {!! $esOrden ? (request('dir','asc') == 'asc' ? '^' : 'v') : '' !!}
                    </a>
                </th>
                <th>
                    @php $esOrden = request('orden') == 'valoracion_restaurante'; @endphp
                    <a href="{{ route('admin.restaurantes.index', array_merge(request()->except('orden','dir'), ['orden' => 'valoracion_restaurante', 'dir' => $esOrden && request('dir','asc') == 'asc' ? 'desc' : 'asc'])) }}" class="admin-th-link {{ $esOrden ? 'activo' : '' }}">
                        Valoración {!! $esOrden ? (request('dir','asc') == 'asc' ? '^' : 'v') : '' !!}
                    </a>
                </th>
                <th>Estilos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($restaurantes as $restaurante)
                <tr>
                    <td data-label="ID">{{ $restaurante->id_restaurante }}</td>
                    <td data-label="Imagen">
                        @if($restaurante->imagenPrincipal)
                            <img src="{{ $restaurante->imagenPrincipal->url }}" alt="" class="admin-miniatura">
                        @else
                            <span class="admin-sin-imagen">🍽️</span>
                        @endif
                    </td>
                    <td data-label="Nombre">
                        <strong>{{ $restaurante->nombre_restaurante }}</strong>
                        <br><small class="texto-gris">{{ $restaurante->slug }}</small>
                    </td>
                    <td data-label="Ciudad">{{ $restaurante->ciudad->nombre_ciudad ?? '—' }}</td>
                    <td data-label="Precio">{{ $restaurante->precio_restaurante ? number_format($restaurante->precio_restaurante, 0) . '€' : '—' }}</td>
                    <td data-label="Valoración">
                        @if($restaurante->valoracion_restaurante)
                            <span class="admin-valoracion">
                                <i class="bi bi-star-fill"></i> {{ number_format($restaurante->valoracion_restaurante, 1) }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td data-label="Estilos">
                        @foreach($restaurante->estilos as $estilo)
                            <span class="admin-etiqueta">{{ $estilo->nombre_estilo }}</span>
                        @endforeach
                    </td>
                    <td data-label="Acciones" class="admin-acciones">
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
                    <td colspan="8" class="admin-vacio">No se encontraron restaurantes con esos filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginacion -->
@if($restaurantes->hasPages())
<div class="admin-paginacion">
    <div class="admin-paginacion-enlaces">
        {{-- Anterior --}}
        @if($restaurantes->onFirstPage())
            <span class="admin-pagina disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <a href="{{ $restaurantes->previousPageUrl() }}" class="admin-pagina"><i class="bi bi-chevron-left"></i></a>
        @endif

        {{-- Numeros de pagina --}}
        @php
            $paginaActual = $restaurantes->currentPage();
            $ultimaPagina = $restaurantes->lastPage();
            $rango = 2;
            $inicio = max(1, $paginaActual - $rango);
            $fin = min($ultimaPagina, $paginaActual + $rango);
        @endphp

        @if($inicio > 1)
            <a href="{{ $restaurantes->url(1) }}" class="admin-pagina">1</a>
            @if($inicio > 2)
                <span class="admin-pagina puntos">…</span>
            @endif
        @endif

        @for($i = $inicio; $i <= $fin; $i++)
            @if($i == $paginaActual)
                <span class="admin-pagina activa">{{ $i }}</span>
            @else
                <a href="{{ $restaurantes->url($i) }}" class="admin-pagina">{{ $i }}</a>
            @endif
        @endfor

        @if($fin < $ultimaPagina)
            @if($fin < $ultimaPagina - 1)
                <span class="admin-pagina puntos">…</span>
            @endif
            <a href="{{ $restaurantes->url($ultimaPagina) }}" class="admin-pagina">{{ $ultimaPagina }}</a>
        @endif

        {{-- Siguiente --}}
        @if($restaurantes->hasMorePages())
            <a href="{{ $restaurantes->nextPageUrl() }}" class="admin-pagina"><i class="bi bi-chevron-right"></i></a>
        @else
            <span class="admin-pagina disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
    <div class="admin-paginacion-info">
        Página {{ $restaurantes->currentPage() }} de {{ $restaurantes->lastPage() }}
    </div>
</div>
@endif
@endsection
