@extends('layouts.admin')

@section('titulo', 'Nuevo Restaurante - Admin')

@section('contenido')
<div class="admin-cabecera-seccion">
    <h1>Nuevo Restaurante</h1>
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

<form method="POST" action="{{ route('admin.restaurantes.guardar') }}" enctype="multipart/form-data" class="admin-formulario">
    @csrf

    <div class="admin-grid-form">
        <!-- Nombre -->
        <div class="campo-formulario">
            <label for="nombre_restaurante">Nombre del restaurante *</label>
            <input
                type="text"
                id="nombre_restaurante"
                name="nombre_restaurante"
                value="{{ old('nombre_restaurante') }}"
                required
                maxlength="255"
                data-msg-required="El nombre del restaurante es obligatorio."
                data-msg-invalid="El nombre del restaurante no puede contener números."
            >
            <small class="js-error" id="error_nombre_restaurante" aria-live="polite"></small>
        </div>

        <!-- País (filtro para ciudades) -->
        <div class="campo-formulario">
            <label for="filtro_pais">País</label>
            <select id="filtro_pais" name="pais">
                <option value="">Todos los países</option>
                @foreach($paises as $pais)
                    <option value="{{ $pais->id_pais }}" {{ old('pais') == $pais->id_pais ? 'selected' : '' }}>
                        {{ $pais->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Comunidad (filtro para ciudades) -->
        <div class="campo-formulario">
            <label for="filtro_comunidad">Comunidad</label>
            <select id="filtro_comunidad" name="comunidad">
                <option value="">Todas las comunidades</option>
                @foreach($comunidades as $comunidad)
                    <option value="{{ $comunidad->id_comunidad }}" {{ old('comunidad') == $comunidad->id_comunidad ? 'selected' : '' }}>
                        {{ $comunidad->nombre_comunidad }}
                    </option>
                @endforeach
            </select>
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
                    <option value="{{ $ciudad->id_ciudad }}" {{ old('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
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
                value="{{ old('telefono_restaurante') }}"
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
            <input type="number" id="precio_restaurante" name="precio_restaurante" step="0.01" min="0" value="{{ old('precio_restaurante') }}">
        </div>

        <!-- Valoracion -->
        <div class="campo-formulario">
            <label for="valoracion_restaurante">Valoración (0-5)</label>
            <input type="number" id="valoracion_restaurante" name="valoracion_restaurante" step="0.1" min="0" max="5" value="{{ old('valoracion_restaurante') }}">
        </div>

        <!-- Web -->
        <div class="campo-formulario">
            <label for="web_real_restaurante">Página web *</label>
            <input
                type="url"
                id="web_real_restaurante"
                name="web_real_restaurante"
                value="{{ old('web_real_restaurante') }}"
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
        <textarea id="descripcion_restaurante" name="descripcion_restaurante" rows="4">{{ old('descripcion_restaurante') }}</textarea>
    </div>

    <!-- Estilos de cocina -->
    <div class="campo-formulario" id="grupo_estilos">
        <label>Estilos de cocina *</label>
        <div class="admin-checkboxes">
            @foreach($estilos as $estilo)
                <label class="admin-checkbox">
                    <input type="checkbox" name="estilos[]" value="{{ $estilo->id_estilo }}"
                        {{ is_array(old('estilos')) && in_array($estilo->id_estilo, old('estilos')) ? 'checked' : '' }}>
                    {{ $estilo->nombre_estilo }}
                </label>
            @endforeach
        </div>
        <small class="js-error" id="error_estilos" aria-live="polite"></small>
    </div>

    <!-- Imagenes -->
    <div class="campo-formulario">
        <label for="imagenes">Imágenes</label>
        <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*" class="campo-archivo">
        <small class="texto-gris">Puedes seleccionar varias imágenes a la vez.</small>
    </div>

    <div class="admin-form-acciones">
        <button type="submit" class="btn-admin-guardar">
            <i class="bi bi-check-lg"></i> Crear restaurante
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
    const pais = document.getElementById('filtro_pais');
    const comunidad = document.getElementById('filtro_comunidad');
    const tel = document.getElementById('telefono_restaurante');
    const web = document.getElementById('web_real_restaurante');
    const estilosContainer = document.getElementById('grupo_estilos');
    const estilosChecks = Array.from(document.querySelectorAll('input[name="estilos[]"]'));
    const errorNombre = document.getElementById('error_nombre_restaurante');
    const errorCiudad = document.getElementById('error_id_ciudad');
    const errorTel = document.getElementById('error_telefono_restaurante');
    const errorWeb = document.getElementById('error_web_real_restaurante');
    const errorEstilos = document.getElementById('error_estilos');

    const nombreRegex = /\d/; // si contiene cualquier dígito → inválido
    const telefonoRegex = /^\d{9}$/;

    // --- selects dependientes (pais -> comunidades; comunidad -> ciudades) ---
    const allComunidadesHtml = comunidad ? comunidad.innerHTML : '';
    const allCiudadesHtml = ciudad ? ciudad.innerHTML : '';

    async function cargarComunidades(idPais, comunidadSeleccionada = '') {
        if (!comunidad) return;
        if (!idPais) {
            comunidad.innerHTML = allComunidadesHtml;
            comunidad.value = '';
            return;
        }

        const res = await fetch(`/api/paises/${idPais}/comunidades`, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('No se pudieron cargar comunidades');
        const data = await res.json();

        comunidad.innerHTML = '<option value="">Todas las comunidades</option>';
        data.forEach((c) => {
            const opt = document.createElement('option');
            opt.value = String(c.id_comunidad);
            opt.textContent = c.nombre_comunidad;
            comunidad.appendChild(opt);
        });
        if (comunidadSeleccionada) comunidad.value = String(comunidadSeleccionada);
    }

    async function cargarCiudades(idComunidad, ciudadSeleccionada = '') {
        if (!ciudad) return;
        if (!idComunidad) {
            ciudad.innerHTML = allCiudadesHtml;
            ciudad.value = '';
            return;
        }

        const res = await fetch(`/api/comunidades/${idComunidad}/ciudades`, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('No se pudieron cargar ciudades');
        const data = await res.json();

        ciudad.innerHTML = '<option value="">Seleccionar ciudad</option>';
        data.forEach((c) => {
            const opt = document.createElement('option');
            opt.value = String(c.id_ciudad);
            opt.textContent = c.nombre_ciudad;
            ciudad.appendChild(opt);
        });
        if (ciudadSeleccionada) ciudad.value = String(ciudadSeleccionada);
    }

    if (pais) {
        pais.addEventListener('change', async function () {
            try {
                // al cambiar país: limpiamos comunidad y ciudad
                if (comunidad) comunidad.value = '';
                if (ciudad) {
                    ciudad.innerHTML = allCiudadesHtml;
                    ciudad.value = '';
                }
                await cargarComunidades(this.value);
            } catch (e) {
                // si falla, mantenemos la lista completa
                if (comunidad) comunidad.innerHTML = allComunidadesHtml;
            }
        });
    }

    if (comunidad) {
        comunidad.addEventListener('change', async function () {
            try {
                await cargarCiudades(this.value);
            } catch (e) {
                // si falla, mantenemos la lista completa
                if (ciudad) ciudad.innerHTML = allCiudadesHtml;
            }
        });
    }

    // si venimos de un error de validación con valores antiguos
    (async () => {
        try {
            const oldPais = pais?.value || '';
            const oldComunidad = comunidad?.value || '';
            const oldCiudad = ciudad?.value || '';
            if (oldPais) await cargarComunidades(oldPais, oldComunidad);
            if (oldComunidad) await cargarCiudades(oldComunidad, oldCiudad);
        } catch (e) {
            // noop
        }
    })();

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

    const validateCiudad = () => {
        const value = (ciudad?.value || '').trim();
        if (!value) {
            setError(ciudad, errorCiudad, ciudad?.dataset.msgRequired || 'Selecciona una ciudad.');
            return false;
        }
        setError(ciudad, errorCiudad, '');
        return true;
    };

    const validateWeb = () => {
        const value = (web?.value || '').trim();
        if (!value) {
            setError(web, errorWeb, web?.dataset.msgRequired || 'La página web es obligatoria.');
            return false;
        }

        // usa la validación nativa del input type=url
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
    if (tel) {
        tel.addEventListener('blur', validateTelefono);
        tel.addEventListener('input', validateTelefono);
    }

    if (ciudad) {
        ciudad.addEventListener('blur', validateCiudad);
        ciudad.addEventListener('change', validateCiudad);
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
        if (!ok) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
