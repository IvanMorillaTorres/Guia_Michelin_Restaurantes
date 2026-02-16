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
            <input
                type="text"
                id="nombre_restaurante"
                name="nombre_restaurante"
                value="{{ old('nombre_restaurante', $restaurante->nombre_restaurante) }}"
                required
                maxlength="255"
                data-msg-required="El nombre del restaurante es obligatorio."
                data-msg-invalid="El nombre del restaurante no puede contener números."
            >
            <small class="js-error" id="error_nombre_restaurante" aria-live="polite"></small>
        </div>

        <!-- Ciudad -->
        <div class="campo-formulario">
            <label for="id_ciudad">Ciudad *</label>
            <select
                id="id_ciudad"
                name="id_ciudad"
                required
                data-msg-required="Selecciona una ciudad."
            >
                <option value="">Seleccionar ciudad</option>
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" {{ old('id_ciudad', $restaurante->id_ciudad) == $ciudad->id_ciudad ? 'selected' : '' }}>
                        {{ $ciudad->nombre_ciudad }}
                    </option>
                @endforeach
            </select>
            <small class="js-error" id="error_id_ciudad" aria-live="polite"></small>
        </div>

        <!-- Telefono -->
        <div class="campo-formulario">
            <label for="telefono_restaurante">Teléfono *</label>
            <input
                type="text"
                id="telefono_restaurante"
                name="telefono_restaurante"
                value="{{ old('telefono_restaurante', $restaurante->telefono_restaurante) }}"
                required
                maxlength="9"
                inputmode="tel"
                placeholder="600000000"
                data-msg-required="El teléfono es obligatorio."
                data-msg-invalid="El teléfono debe tener exactamente 9 números."
            >
            <small class="js-error" id="error_telefono_restaurante" aria-live="polite"></small>
        </div>

        <!-- Precio -->
        <div class="campo-formulario">
            <label for="precio_restaurante">Precio medio (€)</label>
            <input type="number" id="precio_restaurante" name="precio_restaurante" step="0.01" min="0" value="{{ old('precio_restaurante', $restaurante->precio_restaurante) }}">
        </div>

        <!-- Web -->
        <div class="campo-formulario">
            <label for="web_real_restaurante">Página web *</label>
            <input
                type="url"
                id="web_real_restaurante"
                name="web_real_restaurante"
                value="{{ old('web_real_restaurante', $restaurante->web_real_restaurante) }}"
                required
                maxlength="255"
                placeholder="https://..."
                data-msg-required="La página web es obligatoria."
                data-msg-invalid="La página web debe ser una URL válida (ej. https://...)."
            >
            <small class="js-error" id="error_web_real_restaurante" aria-live="polite"></small>
        </div>
    </div>

    <!-- Descripcion -->
    <div class="campo-formulario">
        <label for="descripcion_restaurante">Descripción</label>
        <textarea id="descripcion_restaurante" name="descripcion_restaurante" rows="4">{{ old('descripcion_restaurante', $restaurante->descripcion_restaurante) }}</textarea>
    </div>

    <!-- Estilos de cocina -->
    <div class="campo-formulario" id="grupo_estilos">
        <label>Estilos de cocina *</label>
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
        <small class="js-error" id="error_estilos" aria-live="polite"></small>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form.admin-formulario');
    if (!form) return;

    const nombre = document.getElementById('nombre_restaurante');
    const ciudad = document.getElementById('id_ciudad');
    const tel = document.getElementById('telefono_restaurante');
    const web = document.getElementById('web_real_restaurante');
    const estilosContainer = document.getElementById('grupo_estilos');
    const estilosChecks = Array.from(document.querySelectorAll('input[name="estilos[]"]'));

    const errorNombre = document.getElementById('error_nombre_restaurante');
    const errorCiudad = document.getElementById('error_id_ciudad');
    const errorTel = document.getElementById('error_telefono_restaurante');
    const errorWeb = document.getElementById('error_web_real_restaurante');
    const errorEstilos = document.getElementById('error_estilos');

    const nombreRegex = /\d/;
    const telefonoRegex = /^\d{9}$/;

    const setError = (field, errorEl, msg) => {
        if (!field || !errorEl) return;
        if (msg) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            errorEl.textContent = '';
            errorEl.style.display = 'none';
        }
    };

    const validateNombre = () => {
        const value = (nombre?.value || '').trim();
        if (!value) {
            setError(nombre, errorNombre, nombre?.dataset.msgRequired || 'El nombre es obligatorio.');
            return false;
        }
        if (nombreRegex.test(value)) {
            setError(nombre, errorNombre, nombre?.dataset.msgInvalid || 'No se permiten números.');
            return false;
        }
        setError(nombre, errorNombre, '');
        return true;
    };

    const validateCiudad = () => {
        const value = (ciudad?.value || '').trim();
        if (!value) {
            setError(ciudad, errorCiudad, ciudad?.dataset.msgRequired || 'Selecciona una ciudad.');
            return false;
        }
        setError(ciudad, errorCiudad, '');
        return true;
    };

    const validateTelefono = () => {
        const value = (tel?.value || '').trim();
        if (!value) {
            setError(tel, errorTel, tel?.dataset.msgRequired || 'El teléfono es obligatorio.');
            return false;
        }
        if (!telefonoRegex.test(value)) {
            setError(tel, errorTel, tel?.dataset.msgInvalid || 'Teléfono no válido.');
            return false;
        }
        setError(tel, errorTel, '');
        return true;
    };

    const validateWeb = () => {
        const value = (web?.value || '').trim();
        if (!value) {
            setError(web, errorWeb, web?.dataset.msgRequired || 'La página web es obligatoria.');
            return false;
        }
        if (web && web.validity && web.validity.typeMismatch) {
            setError(web, errorWeb, web?.dataset.msgInvalid || 'La página web no es válida.');
            return false;
        }
        setError(web, errorWeb, '');
        return true;
    };

    const validateEstilos = () => {
        const anyChecked = estilosChecks.some(chk => chk.checked);
        if (!anyChecked) {
            if (estilosContainer) estilosContainer.classList.add('is-invalid');
            if (errorEstilos) {
                errorEstilos.textContent = 'Debes seleccionar al menos 1 estilo de cocina.';
                errorEstilos.style.display = 'block';
            }
            return false;
        }
        if (estilosContainer) estilosContainer.classList.remove('is-invalid');
        if (errorEstilos) {
            errorEstilos.textContent = '';
            errorEstilos.style.display = 'none';
        }
        return true;
    };

    if (nombre) {
        nombre.addEventListener('blur', validateNombre);
        nombre.addEventListener('input', validateNombre);
    }
    if (ciudad) {
        ciudad.addEventListener('blur', validateCiudad);
        ciudad.addEventListener('change', validateCiudad);
    }
    if (tel) {
        tel.addEventListener('blur', validateTelefono);
        tel.addEventListener('input', validateTelefono);
    }
    if (web) {
        web.addEventListener('blur', validateWeb);
        web.addEventListener('input', validateWeb);
    }
    if (estilosChecks.length) {
        estilosChecks.forEach(chk => chk.addEventListener('change', validateEstilos));
    }

    form.addEventListener('submit', function (e) {
        const okNombre = validateNombre();
        const okCiudad = validateCiudad();
        const okTel = validateTelefono();
        const okWeb = validateWeb();
        const okEstilos = validateEstilos();
        const ok = okNombre && okCiudad && okTel && okWeb && okEstilos;
        if (!ok) e.preventDefault();
    });
});
</script>
@endsection
