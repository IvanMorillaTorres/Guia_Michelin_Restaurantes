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

            <!-- Valoracion -->
            <div class="valoracion-grande">
                <span class="estrellas">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($restaurante->valoracion_restaurante))
                            <i class="bi bi-star-fill"></i>
                        @elseif($i - 0.5 <= $restaurante->valoracion_restaurante)
                            <i class="bi bi-star-half"></i>
                        @else
                            <i class="bi bi-star"></i>
                        @endif
                    @endfor
                </span>
                <span class="texto-valoracion">{{ number_format($restaurante->valoracion_restaurante, 1) }} / 5.0</span>
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
