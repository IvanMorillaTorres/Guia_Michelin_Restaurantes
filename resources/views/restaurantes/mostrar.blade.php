@extends('layouts.app')

@section('titulo', $restaurante->nombre_restaurante . ' - Guía MICHELIN')

@section('contenido')

<!-- Portada con imagen del restaurante -->
<section class="detalle-portada">
    @if($restaurante->imagenes->count() > 0)
        <img src="{{ $restaurante->imagenes->first()->url }}" alt="{{ $restaurante->nombre_restaurante }}">
    @else
        <div class="portada-vacia"></div>
    @endif
    <div class="portada-capa">
        <div class="container">
            <div class="portada-insignias">
                @foreach($restaurante->estilos as $estilo)
                    <span class="insignia-grande michelin">{{ $estilo->nombre_estilo }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- Volver al listado -->
    <div class="detalle-volver">
        <a href="{{ route('restaurantes.index') }}">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    <div class="detalle-distribucion">
        <!-- Columna principal -->
        <div class="detalle-principal">
            <div class="detalle-cabecera">
                <h1>{{ $restaurante->nombre_restaurante }}</h1>
                <div class="detalle-meta">
                    <span class="tipo-cocina">
                        @foreach($restaurante->estilos as $estilo)
                            {{ $estilo->nombre_estilo }}@if(!$loop->last) · @endif
                        @endforeach
                    </span>
                    @if($restaurante->precio_restaurante)
                        <span class="rango-precio">{{ number_format($restaurante->precio_restaurante, 0) }}€</span>
                    @endif
                </div>
            </div>

            <!-- Valoracion media (sin estrellas estaticas) -->
            <div class="valoracion-grande">
                <span class="texto-valoracion">
                    Media: <span id="media-valoracion">{{ number_format($restaurante->valoracion_restaurante, 1) }}</span> / 5.0
                    (<span id="recuento-valoraciones">{{ $restaurante->valoraciones_count ?? 0 }}</span> valoraciones)
                </span>
            </div>

            <!-- Valorar -->
            <div class="detalle-seccion">
                <h2>Tu valoración</h2>
                @if(session('valoracion_ok'))
                    <div class="alert alert-success">{{ session('valoracion_ok') }}</div>
                @endif
                <form id="form-valoracion" method="POST" action="{{ route('restaurantes.valorar', $restaurante->slug) }}">
                    @csrf
                    <input type="hidden" name="puntuacion" id="input-puntuacion" value="{{ (int)($valoracionUsuario ?? 0) }}">

                    <div class="valoracion-interactiva" aria-label="Valora este restaurante">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                class="estrella-btn"
                                data-valor="{{ $i }}"
                                aria-label="Valorar con {{ $i }} estrellas">
                                @if(isset($valoracionUsuario) && (int)$valoracionUsuario >= $i)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            </button>
                        @endfor

                        <span class="ms-2 texto-valoracion" id="texto-tu-nota">
                            @if(isset($valoracionUsuario) && (int)$valoracionUsuario > 0)
                                Tu nota: {{ (int)$valoracionUsuario }}/5
                            @else
                                Pulsa una estrella
                            @endif
                        </span>
                    </div>

                    @error('puntuacion')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const form = document.getElementById('form-valoracion');
                        const input = document.getElementById('input-puntuacion');
                        const textoTuNota = document.getElementById('texto-tu-nota');
                        const mediaEl = document.getElementById('media-valoracion');
                        const recuentoEl = document.getElementById('recuento-valoraciones');
                        const lateralCountEl = document.getElementById('valoraciones-count');

                        if (!form || !input) return;

                        const botones = Array.from(form.querySelectorAll('.estrella-btn'));

                        function pintarEstrellas(valor) {
                            botones.forEach((btn) => {
                                const v = parseInt(btn.getAttribute('data-valor'));
                                const icon = btn.querySelector('i');
                                if (!icon) return;
                                icon.className = (v <= valor) ? 'bi bi-star-fill' : 'bi bi-star';
                            });
                        }

                        botones.forEach((btn) => {
                            btn.addEventListener('click', async function () {
                                const valor = parseInt(this.getAttribute('data-valor'));
                                input.value = String(valor);

                                // UX basica: pintar al momento y desactivar mientras enviamos
                                pintarEstrellas(valor);
                                if (textoTuNota) textoTuNota.textContent = 'Guardando...';
                                botones.forEach(b => b.disabled = true);

                                try {
                                    const formData = new FormData(form);
                                    const respuesta = await fetch(form.action, {
                                        method: 'POST',
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        body: formData
                                    });

                                    if (!respuesta.ok) {
                                        throw new Error('Respuesta no OK');
                                    }

                                    const data = await respuesta.json();
                                    if (!data || data.ok !== true) {
                                        throw new Error('JSON no valido');
                                    }

                                    if (textoTuNota) textoTuNota.textContent = `Tu nota: ${data.user}/5`;
                                    if (mediaEl) mediaEl.textContent = Number(data.media).toFixed(1);
                                    if (recuentoEl) recuentoEl.textContent = String(data.count);
                                    if (lateralCountEl) lateralCountEl.textContent = String(data.count);
                                } catch (e) {
                                    // fallback simple: si falla AJAX, hacemos submit normal
                                    botones.forEach(b => b.disabled = false);
                                    form.submit();
                                    return;
                                }

                                botones.forEach(b => b.disabled = false);
                            });
                        });
                    });
                </script>
            </div>

            <!-- Descripcion -->
            @if($restaurante->descripcion_restaurante)
                <div class="detalle-seccion">
                    <h2>Descripción</h2>
                    <p class="descripcion">{{ $restaurante->descripcion_restaurante }}</p>
                </div>
            @endif

            {{-- Mapa con ubicacion (debajo de la descripcion) --}}
            <div class="detalle-seccion">
                <h2>Ubicación</h2>
                <iframe
                    class="mapa-detalle"
                    @if(!empty($restaurante->latitud) && !empty($restaurante->longitud))
                        src="https://www.google.com/maps?q={{ $restaurante->latitud }},{{ $restaurante->longitud }}&output=embed"
                    @else
                        src="https://www.google.com/maps?q={{ urlencode($restaurante->nombre_restaurante . ' restaurante ' . ($restaurante->ciudad->nombre_ciudad ?? '')) }}&output=embed"
                    @endif
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Galeria de imagenes -->
            @if($restaurante->imagenes->count() > 1)
                <div class="detalle-seccion">
                    <h2>Galería</h2>
                    <div class="galeria-imagenes">
                        @foreach($restaurante->imagenes as $imagen)
                            <img src="{{ $imagen->url }}" alt="{{ $restaurante->nombre_restaurante }}">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Barra lateral con informacion -->
        <aside class="detalle-lateral">
            <div class="tarjeta-informacion">
                <h3>Información</h3>

                <div class="info-elemento">
                    <strong><i class="bi bi-star-fill"></i> Valoraciones</strong>
                    <p><span id="valoraciones-count">{{ $restaurante->valoraciones_count ?? 0 }}</span></p>
                </div>

                @if($restaurante->ciudad)
                    <div class="info-elemento">
                        <strong><i class="bi bi-geo-alt-fill"></i> Ciudad</strong>
                        <p>{{ $restaurante->ciudad->nombre_ciudad }}</p>
                    </div>
                @endif

                @if($restaurante->ciudad && $restaurante->ciudad->comunidad)
                    <div class="info-elemento">
                        <strong>Comunidad</strong>
                        <p>{{ $restaurante->ciudad->comunidad->nombre_comunidad }}</p>
                    </div>
                @endif

                @if($restaurante->ciudad && $restaurante->ciudad->comunidad && $restaurante->ciudad->comunidad->pais)
                    <div class="info-elemento">
                        <strong>País</strong>
                        <p>{{ $restaurante->ciudad->comunidad->pais->nombre }}</p>
                    </div>
                @endif

                @if($restaurante->telefono_restaurante)
                    <div class="info-elemento">
                        <strong><i class="bi bi-telephone-fill"></i> Teléfono</strong>
                        <p><a href="tel:{{ $restaurante->telefono_restaurante }}">{{ $restaurante->telefono_restaurante }}</a></p>
                    </div>
                @endif

                @if($restaurante->web_real_restaurante)
                    <div class="info-elemento">
                        <strong><i class="bi bi-globe2"></i> Web</strong>
                        <p><a href="{{ $restaurante->web_real_restaurante }}" target="_blank">Visitar web oficial</a></p>
                    </div>
                @endif

                @if($restaurante->precio_restaurante)
                    <div class="info-elemento info-precio">
                        <strong>Precio medio</strong>
                        <p class="precio-grande">{{ number_format($restaurante->precio_restaurante, 0) }}€</p>
                    </div>
                @endif
            </div>
        </aside>
    </div>

    <!-- Restaurantes parecidos -->
    @if($parecidos->count() > 0)
        <section class="restaurantes-similares">
            <h2>Restaurantes similares</h2>
            <div class="cuadricula-restaurantes">
                @foreach($parecidos as $parecido)
                    <article class="tarjeta-restaurante">
                        <a href="{{ route('restaurantes.mostrar', $parecido->slug) }}" class="tarjeta-enlace">
                            <div class="tarjeta-imagen">
                                @if($parecido->imagenPrincipal)
                                    <img src="{{ $parecido->imagenPrincipal->url }}" alt="{{ $parecido->nombre_restaurante }}">
                                @else
                                    <div class="imagen-vacia"></div>
                                @endif
                            </div>
                            <div class="tarjeta-contenido">
                                <h3>{{ $parecido->nombre_restaurante }}</h3>
                                <div class="tarjeta-info">
                                    <span class="cocina">
                                        @foreach($parecido->estilos as $estilo)
                                            {{ $estilo->nombre_estilo }}@if(!$loop->last), @endif
                                        @endforeach
                                    </span>
                                </div>
                                <div class="tarjeta-meta">
                                    <span class="ubicacion">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $parecido->ciudad->nombre_ciudad ?? '' }}
                                    </span>
                                    @if($parecido->precio_restaurante)
                                        <span class="precio">{{ number_format($parecido->precio_restaurante, 0) }}€</span>
                                    @endif
                                </div>
                                <div class="valoracion">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($parecido->valoracion_restaurante))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $parecido->valoracion_restaurante)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
