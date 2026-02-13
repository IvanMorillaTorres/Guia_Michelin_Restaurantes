@extends('layouts.app')

@section('titulo', 'Restaurantes - Guía MICHELIN')

@section('contenido')

<div class="pagina-restaurantes">
    <div class="contenedor-ancho">

        <!-- Barra de busqueda -->
        <form method="GET" action="{{ route('restaurantes.index') }}" id="formularioFiltros">
            <div class="fila-busqueda">
                <br>
                <div class="campo-busqueda-wrapper">
                    <i class="bi bi-search icono-busqueda"></i>
                    <input type="text" name="busqueda" class="campo-busqueda-grande"
                        placeholder="Buscar restaurantes, ciudades..."
                        value="{{ request('busqueda') }}">
                </div>
            </div>

            <!-- Filtros como pills -->
            <div class="fila-filtros">
                <div class="filtros-izquierda">

                    <!-- Ciudad -->
                    <label class="etiqueta-filtro etiqueta-select">
                        <span>Ciudad</span>
                        <select name="ciudad" class="select-oculto" onchange="document.getElementById('formularioFiltros').submit()">
                            <option value="">Todas las ciudades</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id_ciudad }}" {{ request('ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                                    {{ $ciudad->nombre_ciudad }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <!-- Estilo de cocina -->
                    <label class="etiqueta-filtro etiqueta-select">
                        <span>Cocina</span>
                        <select name="estilos[]" class="select-oculto" onchange="document.getElementById('formularioFiltros').submit()">
                            <option value="">Todos los estilos</option>
                            @foreach($estilos as $estilo)
                                <option value="{{ $estilo->id_estilo }}"
                                    {{ is_array(request('estilos')) && in_array($estilo->id_estilo, request('estilos')) ? 'selected' : '' }}>
                                    {{ $estilo->nombre_estilo }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <!-- Precio -->
                    <label class="etiqueta-filtro etiqueta-select">
                        <span>Precio</span>
                        <select name="precio_rango" class="select-oculto" onchange="document.getElementById('formularioFiltros').submit()">
                            <option value="">Todos</option>
                            <option value="0-30" {{ request('precio_rango') == '0-30' ? 'selected' : '' }}>Hasta 30€</option>
                            <option value="30-60" {{ request('precio_rango') == '30-60' ? 'selected' : '' }}>30€ - 60€</option>
                            <option value="60-100" {{ request('precio_rango') == '60-100' ? 'selected' : '' }}>60€ - 100€</option>
                            <option value="100+" {{ request('precio_rango') == '100+' ? 'selected' : '' }}>Más de 100€</option>
                        </select>
                    </label>

                    <!-- Valoracion -->
                    <label class="etiqueta-filtro etiqueta-select">
                        <span>Valoración</span>
                        <select name="valoracion_min" class="select-oculto" onchange="document.getElementById('formularioFiltros').submit()">
                            <option value="">Todas</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('valoracion_min') == $i ? 'selected' : '' }}>
                                    {{ $i }}+ estrellas
                                </option>
                            @endfor
                        </select>
                    </label>

                    <!-- Ordenar -->
                    <label class="etiqueta-filtro etiqueta-select">
                        <span>Ordenar</span>
                        <select name="orden" class="select-oculto" onchange="document.getElementById('formularioFiltros').submit()">
                            <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="valoracion" {{ request('orden') == 'valoracion' ? 'selected' : '' }}>Valoración</option>
                            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio ↑</option>
                            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio ↓</option>
                        </select>
                    </label>

                </div>
                <div class="filtros-derecha">
                    <a href="{{ route('restaurantes.index') }}" class="etiqueta-filtro etiqueta-limpiar">Limpiar</a>
                </div>
            </div>
        </form>

        <!-- Linea separadora -->
        <div class="linea-separadora" aria-hidden="true"></div>

        <!-- Resultados -->
        <section class="contenido-restaurantes">
            @if($restaurantes->isEmpty())
                <div class="sin-resultados">
                    <i class="bi bi-search" style="font-size: 48px;"></i>
                    <h3>Sin resultados</h3>
                    <p>No se encontraron restaurantes con esos filtros.</p>
                </div>
            @else
                <div class="barra-resultados">
                    <span class="contador-resultados">
                        {{ $restaurantes->firstItem() }}-{{ $restaurantes->lastItem() }} de {{ $restaurantes->total() }} restaurantes
                    </span>
                    @if($restaurantes->nextPageUrl())
                        <a class="boton-siguiente" href="{{ $restaurantes->nextPageUrl() }}" aria-label="Siguiente">›</a>
                    @else
                        <span class="boton-siguiente desactivado" aria-hidden="true">›</span>
                    @endif
                </div>

                <div class="cuadricula-restaurantes">
                    @foreach($restaurantes as $restaurante)
                        <article class="tarjeta-restaurante">
                            <a href="{{ route('restaurantes.mostrar', $restaurante->slug) }}" class="tarjeta-enlace">
                                <div class="tarjeta-imagen">
                                    <!-- Iconos overlay -->
                                    <div class="tarjeta-acciones" aria-hidden="true">
                                        <button
                                            type="button"
                                            class="accion-icono btn-guardar"
                                            data-slug="{{ $restaurante->slug }}"
                                            data-guardado="{{ in_array($restaurante->id_restaurante, $guardadosIds ?? []) ? '1' : '0' }}"
                                            aria-label="Guardar">
                                            <i class="bi {{ in_array($restaurante->id_restaurante, $guardadosIds ?? []) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        </button>
                                        <span class="accion-icono"><i class="bi bi-check-circle"></i></span>
                                        <span class="accion-icono"><i class="bi bi-box-arrow-up-right"></i></span>
                                    </div>

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
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($restaurante->valoracion_restaurante))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $restaurante->valoracion_restaurante)
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                        <span class="numero-valoracion">({{ number_format($restaurante->valoracion_restaurante, 1) }})</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div class="paginacion">
                    {{ $restaurantes->links() }}
                </div>
            @endif
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                document.querySelectorAll('.btn-guardar').forEach((btn) => {
                    btn.addEventListener('click', async function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const slug = this.getAttribute('data-slug');
                        if (!slug || !token) return;

                        try {
                            const res = await fetch(`/restaurante/${slug}/guardar`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            if (!data || data.ok !== true) return;

                            const icon = this.querySelector('i');
                            if (icon) {
                                icon.className = data.guardado ? 'bi bi-heart-fill' : 'bi bi-heart';
                            }
                        } catch (err) {
                            // si falla, no hacemos nada (nivel AJAX muy simple)
                        }
                    });
                });
            });
        </script>

    </div>
</div>
@endsection
