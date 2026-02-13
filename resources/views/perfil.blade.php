@extends('layouts.app')

@section('titulo', 'Perfil - Guía MICHELIN')

@section('contenido')
<div class="container" style="padding: 30px 0;">
    <h1 style="font-weight: 700;">Perfil</h1>
    <p style="color: var(--texto-claro); margin-bottom: 25px;">
        Hola, {{ $usuario->nombre }} {{ $usuario->apellido1 }}! Bienvenido a tu perfil. Aquí puedes ver tus restaurantes guardados y gestionar tu cuenta.
    </p>

    @if (session('perfil_ok'))
        <div class="alert alert-success">{{ session('perfil_ok') }}</div>
    @endif
    @if (session('password_ok'))
        <div class="alert alert-success">{{ session('password_ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            Revisa los campos marcados en rojo.
        </div>
    @endif

    <div class="row g-4" style="margin-bottom: 30px;">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 15px;">Tus datos</h2>

                    <form id="form-perfil" class="needs-validation" novalidate method="POST" action="{{ route('perfil.actualizar') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $usuario->nombre) }}"
                                    required
                                    maxlength="255"
                                >
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Primer apellido *</label>
                                <input
                                    type="text"
                                    name="apellido1"
                                    class="form-control @error('apellido1') is-invalid @enderror"
                                    value="{{ old('apellido1', $usuario->apellido1) }}"
                                    required
                                    maxlength="255"
                                >
                                @error('apellido1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">El primer apellido es obligatorio.</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Segundo apellido</label>
                                <input
                                    type="text"
                                    name="apellido2"
                                    class="form-control @error('apellido2') is-invalid @enderror"
                                    value="{{ old('apellido2', $usuario->apellido2) }}"
                                    maxlength="255"
                                >
                                @error('apellido2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input
                                    id="telefono"
                                    type="text"
                                    name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $usuario->telefono) }}"
                                    maxlength="20"
                                    inputmode="tel"
                                    placeholder="+34 600 000 000"
                                >
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Teléfono no válido.</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email *</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $usuario->email) }}"
                                    required
                                    maxlength="255"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Introduce un email válido.</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input
                                    id="nacimiento"
                                    type="date"
                                    name="nacimiento"
                                    class="form-control @error('nacimiento') is-invalid @enderror"
                                    value="{{ old('nacimiento', optional($usuario->nacimiento)->format('Y-m-d')) }}"
                                >
                                @error('nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">La fecha de nacimiento no puede ser futura.</div>
                                @enderror
                            </div>

                            <div class="col-12" style="margin-top: 10px;">
                                <button type="submit" class="btn btn-primary">
                                    Guardar cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 15px;">Cambiar contraseña</h2>

                    <form id="form-password" class="needs-validation" novalidate method="POST" action="{{ route('perfil.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Contraseña actual *</label>
                            <input
                                type="password"
                                name="password_actual"
                                class="form-control @error('password_actual') is-invalid @enderror"
                                required
                                autocomplete="current-password"
                            >
                            @error('password_actual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">La contraseña actual es obligatoria.</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña *</label>
                            <input
                                id="password_nueva"
                                type="password"
                                name="password_nueva"
                                class="form-control @error('password_nueva') is-invalid @enderror"
                                required
                                minlength="6"
                                autocomplete="new-password"
                            >
                            @error('password_nueva')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">La nueva contraseña debe tener al menos 6 caracteres.</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Repetir nueva contraseña *</label>
                            <input
                                id="password_nueva_confirmation"
                                type="password"
                                name="password_nueva_confirmation"
                                class="form-control"
                                required
                                minlength="6"
                                autocomplete="new-password"
                            >
                            <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 15px;">Restaurantes guardados</h2>

    @if($guardados->isEmpty())
        <div class="sin-resultados">
            <i class="bi bi-heart" style="font-size: 48px;"></i>
            <h3>Sin guardados</h3>
            <p>Aún no has guardado ningún restaurante.</p>
        </div>
    @else
        <div class="cuadricula-restaurantes">
            @foreach($guardados as $restaurante)
                <article class="tarjeta-restaurante">
                    <a href="{{ route('restaurantes.mostrar', $restaurante->slug) }}" class="tarjeta-enlace">
                        <div class="tarjeta-imagen">
                            @if($restaurante->imagenPrincipal)
                                <img src="{{ $restaurante->imagenPrincipal->url }}" alt="{{ $restaurante->nombre_restaurante }}">
                            @else
                                <div class="imagen-vacia">🍽️</div>
                            @endif
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>{{ $restaurante->nombre_restaurante }}</h3>
                            <p class="tarjeta-ubicacion">
                                {{ $restaurante->ciudad->nombre_ciudad ?? 'Sin ciudad' }}
                                @if($restaurante->ciudad && $restaurante->ciudad->comunidad)
                                    · {{ $restaurante->ciudad->comunidad->nombre_comunidad }}
                                @endif
                            </p>
                            <div class="tarjeta-linea">
                                @if($restaurante->precio_restaurante)
                                    <span class="precio">{{ number_format($restaurante->precio_restaurante, 0) }}€</span>
                                    <span class="punto-separador">·</span>
                                @endif
                                <span class="cocina">
                                    @foreach($restaurante->estilos as $estilo)
                                        {{ $estilo->nombre_estilo }}@if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            </div>
                            <div class="valoracion">
                                <span class="numero-valoracion">({{ number_format($restaurante->valoracion_restaurante, 1) }})</span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.needs-validation');

    forms.forEach((form) => {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    const telefono = document.getElementById('telefono');
    if (telefono) {
        const telefonoRegex = /^[0-9+\s()\-]*$/;
        const validateTelefono = () => {
            const value = (telefono.value || '').trim();
            if (!value) {
                telefono.setCustomValidity('');
                return;
            }
            telefono.setCustomValidity(telefonoRegex.test(value) ? '' : 'Telefono no valido');
        };
        telefono.addEventListener('input', validateTelefono);
        validateTelefono();
    }

    const nacimiento = document.getElementById('nacimiento');
    if (nacimiento) {
        const validateNacimiento = () => {
            if (!nacimiento.value) {
                nacimiento.setCustomValidity('');
                return;
            }
            const selected = new Date(nacimiento.value + 'T00:00:00');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            nacimiento.setCustomValidity(selected > today ? 'Fecha futura' : '');
        };
        nacimiento.addEventListener('change', validateNacimiento);
        validateNacimiento();
    }

    const pass = document.getElementById('password_nueva');
    const pass2 = document.getElementById('password_nueva_confirmation');
    if (pass && pass2) {
        const validateMatch = () => {
            pass2.setCustomValidity(pass.value === pass2.value ? '' : 'No coinciden');
        };
        pass.addEventListener('input', validateMatch);
        pass2.addEventListener('input', validateMatch);
        validateMatch();
    }
});
</script>
@endsection
