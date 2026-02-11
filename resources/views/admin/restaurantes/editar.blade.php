@extends('layouts.admin')

@section('titulo', 'Editar: ' . $restaurante->nombre_restaurante . ' - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Editar Restaurante</h1>
    <a href="{{ route('admin.restaurantes.index') }}" class="btn-admin-volver">
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

<form method="POST" action="{{ route('admin.restaurantes.actualizar', $restaurante->id_restaurante) }}" enctype="multipart/form-data" class="admin-formulario">
    @csrf
    @method('PUT')

    <div class="admin-grid-form">
        <!-- Nombre -->
        <div class="campo-formulario">
            <label for="nombre_restaurante">Nombre del restaurante *</label>
            <input type="text" id="nombre_restaurante" name="nombre_restaurante" value="{{ old('nombre_restaurante', $restaurante->nombre_restaurante) }}" required>
        </div>

        <!-- Ciudad -->
        <div class="campo-formulario">
            <label for="id_ciudad">Ciudad *</label>
            <select id="id_ciudad" name="id_ciudad" required>
                <option value="">Seleccionar ciudad</option>
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" {{ old('id_ciudad', $restaurante->id_ciudad) == $ciudad->id_ciudad ? 'selected' : '' }}>
                        {{ $ciudad->nombre_ciudad }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Telefono -->
        <div class="campo-formulario">
            <label for="telefono_restaurante">Teléfono</label>
            <input type="text" id="telefono_restaurante" name="telefono_restaurante" value="{{ old('telefono_restaurante', $restaurante->telefono_restaurante) }}">
        </div>

        <!-- Precio -->
        <div class="campo-formulario">
            <label for="precio_restaurante">Precio medio (€)</label>
            <input type="number" id="precio_restaurante" name="precio_restaurante" step="0.01" min="0" value="{{ old('precio_restaurante', $restaurante->precio_restaurante) }}">
        </div>

        <!-- Valoracion -->
        <div class="campo-formulario">
            <label for="valoracion_restaurante">Valoración (0-5)</label>
            <input type="number" id="valoracion_restaurante" name="valoracion_restaurante" step="0.1" min="0" max="5" value="{{ old('valoracion_restaurante', $restaurante->valoracion_restaurante) }}">
        </div>

        <!-- Web -->
        <div class="campo-formulario">
            <label for="web_real_restaurante">Página web</label>
            <input type="url" id="web_real_restaurante" name="web_real_restaurante" value="{{ old('web_real_restaurante', $restaurante->web_real_restaurante) }}" placeholder="https://...">
        </div>
    </div>

    <!-- Descripcion -->
    <div class="campo-formulario">
        <label for="descripcion_restaurante">Descripción</label>
        <textarea id="descripcion_restaurante" name="descripcion_restaurante" rows="4">{{ old('descripcion_restaurante', $restaurante->descripcion_restaurante) }}</textarea>
    </div>

    <!-- Estilos de cocina -->
    <div class="campo-formulario">
        <label>Estilos de cocina</label>
        <div class="admin-checkboxes">
            @php $estilosActuales = $restaurante->estilos->pluck('id_estilo')->toArray(); @endphp
            @foreach($estilos as $estilo)
                <label class="admin-checkbox">
                    <input type="checkbox" name="estilos[]" value="{{ $estilo->id_estilo }}"
                        {{ in_array($estilo->id_estilo, old('estilos', $estilosActuales)) ? 'checked' : '' }}>
                    {{ $estilo->nombre_estilo }}
                </label>
            @endforeach
        </div>
    </div>

    <!-- Imagenes actuales -->
    @if($restaurante->imagenes->count() > 0)
        <div class="campo-formulario">
            <label>Imágenes actuales</label>
            <div class="admin-galeria">
                @foreach($restaurante->imagenes as $imagen)
                    <div class="admin-galeria-item">
                        <img src="{{ $imagen->url }}" alt="">
                        <label class="admin-eliminar-imagen">
                            <input type="checkbox" name="eliminar_imagenes[]" value="{{ $imagen->id_imagenes }}">
                            <i class="bi bi-trash"></i> Eliminar
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Subir nuevas imagenes -->
    <div class="campo-formulario">
        <label for="imagenes">Añadir más imágenes</label>
        <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*" class="campo-archivo">
        <small class="texto-gris">Puedes seleccionar varias imágenes a la vez.</small>
    </div>

    <div class="admin-form-acciones">
        <button type="submit" class="btn-admin-guardar">
            <i class="bi bi-check-lg"></i> Guardar cambios
        </button>
        <a href="{{ route('admin.restaurantes.index') }}" class="btn-admin-cancelar">Cancelar</a>
    </div>
</form>
@endsection
