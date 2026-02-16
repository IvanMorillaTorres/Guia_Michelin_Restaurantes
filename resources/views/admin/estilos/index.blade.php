@extends('layouts.admin')

@section('titulo', 'Gestionar Estilos de Cocina - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Gestionar Estilos de Cocina</h1>
    <button type="button" class="btn-admin-nuevo" data-bs-toggle="modal" data-bs-target="#modalEstilo"
        data-accion="crear">
        <i class="bi bi-plus-lg"></i> Nuevo estilo
    </button>
</div>

<!-- Filtros -->
<form method="GET" action="{{ route('admin.estilos.index') }}" class="admin-filtros">
    <div class="admin-filtros-fila">
        <div class="admin-filtro-campo">
            <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Buscar por nombre..." class="admin-campo-busqueda">
        </div>
        <div class="admin-filtro-acciones">
            <button type="submit" class="btn-admin-buscar"><i class="bi bi-search"></i> Filtrar</button>
            <a href="{{ route('admin.estilos.index') }}" class="btn-admin-limpiar"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<!-- Tabla de estilos -->
<div class="admin-tabla-responsive">
    <table class="admin-tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Nº Restaurantes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estilos as $estilo)
                <tr>
                    <td data-label="Nombre">
                        <strong>{{ $estilo->nombre_estilo }}</strong>
                    </td>
                    <td data-label="Descripción">
                        {{ $estilo->descripcion_estilo ? Str::limit($estilo->descripcion_estilo, 80) : '—' }}
                    </td>
                    <td data-label="Nº Restaurantes">
                        <span class="admin-etiqueta">{{ $estilo->restaurantes_count }}</span>
                    </td>
                    <td data-label="Acciones" class="admin-acciones">
                        <button type="button" class="btn-admin-editar" title="Editar"
                            data-bs-toggle="modal" data-bs-target="#modalEstilo"
                            data-accion="editar"
                            data-id="{{ $estilo->id_estilo }}"
                            data-nombre="{{ $estilo->nombre_estilo }}"
                            data-descripcion="{{ $estilo->descripcion_estilo ?? '' }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.estilos.eliminar', $estilo->id_estilo) }}" style="display:inline;" onsubmit="confirmarEliminacion(event, '¿Estás seguro de que deseas eliminar el estilo «{{ $estilo->nombre_estilo }}»?')">
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
                    <td colspan="4" class="admin-vacio">No se encontraron estilos de cocina.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
@if($estilos->hasPages())
<div class="admin-paginacion">
    <div class="admin-paginacion-enlaces">
        @if($estilos->onFirstPage())
            <span class="admin-pagina disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <a href="{{ $estilos->previousPageUrl() }}" class="admin-pagina"><i class="bi bi-chevron-left"></i></a>
        @endif

        @php
            $paginaActual = $estilos->currentPage();
            $ultimaPagina = $estilos->lastPage();
            $rango = 2;
            $inicio = max(1, $paginaActual - $rango);
            $fin = min($ultimaPagina, $paginaActual + $rango);
        @endphp

        @if($inicio > 1)
            <a href="{{ $estilos->url(1) }}" class="admin-pagina">1</a>
            @if($inicio > 2)
                <span class="admin-pagina puntos">…</span>
            @endif
        @endif

        @for($i = $inicio; $i <= $fin; $i++)
            @if($i == $paginaActual)
                <span class="admin-pagina activa">{{ $i }}</span>
            @else
                <a href="{{ $estilos->url($i) }}" class="admin-pagina">{{ $i }}</a>
            @endif
        @endfor

        @if($fin < $ultimaPagina)
            @if($fin < $ultimaPagina - 1)
                <span class="admin-pagina puntos">…</span>
            @endif
            <a href="{{ $estilos->url($ultimaPagina) }}" class="admin-pagina">{{ $ultimaPagina }}</a>
        @endif

        @if($estilos->hasMorePages())
            <a href="{{ $estilos->nextPageUrl() }}" class="admin-pagina"><i class="bi bi-chevron-right"></i></a>
        @else
            <span class="admin-pagina disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
    <div class="admin-paginacion-info">
        Página {{ $estilos->currentPage() }} de {{ $estilos->lastPage() }}
    </div>
</div>
@endif

<!-- Modal para crear/editar estilo -->
<div id="modalEstilo" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEstilo" method="POST" action="{{ route('admin.estilos.guardar') }}">
                @csrf
                <input type="hidden" id="metodoEstilo" name="_method" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstiloTitulo">Nuevo Estilo de Cocina</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alerta-error" style="margin-bottom:1rem;">
                            @foreach($errors->all() as $error)
                                <p><i class="bi bi-exclamation-circle"></i> {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="campo-formulario" style="margin-bottom:1rem;">
                        <label for="nombre_estilo">Nombre del estilo *</label>
                        <input type="text" id="nombre_estilo" name="nombre_estilo" value="{{ old('nombre_estilo') }}" required maxlength="255" class="form-control">
                    </div>
                    <div class="campo-formulario">
                        <label for="descripcion_estilo">Descripción</label>
                        <textarea id="descripcion_estilo" name="descripcion_estilo" rows="3" maxlength="1000" class="form-control">{{ old('descripcion_estilo') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-admin-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-admin-guardar">
                        <i class="bi bi-check-lg"></i> <span id="modalEstiloBoton">Crear estilo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Este script se renderiza DESPUÉS del Bootstrap JS del layout --}}
@push('scripts')
<script>
    var modalEl = document.getElementById('modalEstilo');
    modalEl.addEventListener('show.bs.modal', function (event) {
        var boton = event.relatedTarget;
        var accion = boton.getAttribute('data-accion');
        var form = document.getElementById('formEstilo');
        var metodo = document.getElementById('metodoEstilo');
        var titulo = document.getElementById('modalEstiloTitulo');
        var botonTexto = document.getElementById('modalEstiloBoton');
        var nombre = document.getElementById('nombre_estilo');
        var descripcion = document.getElementById('descripcion_estilo');

        if (accion === 'editar') {
            form.action = '/admin/estilos/' + boton.getAttribute('data-id');
            metodo.value = 'PUT';
            titulo.textContent = 'Editar Estilo de Cocina';
            botonTexto.textContent = 'Actualizar estilo';
            nombre.value = boton.getAttribute('data-nombre');
            descripcion.value = boton.getAttribute('data-descripcion');
        } else {
            form.action = "{{ route('admin.estilos.guardar') }}";
            metodo.value = 'POST';
            titulo.textContent = 'Nuevo Estilo de Cocina';
            botonTexto.textContent = 'Crear estilo';
            nombre.value = '';
            descripcion.value = '';
        }
    });
</script>
@endpush
