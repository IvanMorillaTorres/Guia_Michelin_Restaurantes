@extends('layouts.app')

@section('titulo', 'Restaurantes - Guía MICHELIN')

@section('contenido')

<!-- Portada de la pagina de restaurantes -->
<section class="portada">
    <div class="container">
        <h1>Restaurantes</h1>
        <p>Descubre los mejores restaurantes seleccionados por la Guía MICHELIN</p>
    </div>
</section>

<div class="pagina-restaurantes">
    <div class="container">
        <div class="distribucion-restaurantes">

            <!-- Barra lateral de filtros -->
            <aside class="barra-filtros">
                <h3>Filtros</h3>
                <form method="GET" action="{{ route('restaurantes.index') }}" id="formularioFiltros">

                    <!-- Filtro por ciudad -->
                    <div class="grupo-filtro">
                        <label>Ciudad</label>
                        <select name="ciudad" class="filtro-selector" onchange="this.form.submit()">
                            <option value="">Todas las ciudades</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id_ciudad }}" {{ request('ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                                    {{ $ciudad->nombre_ciudad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por estilo de cocina -->
                    <div class="grupo-filtro">
                        <label>Estilo de cocina</label>
                        @foreach($estilos as $estilo)
                            <div class="filtro-casilla">
                                <input type="checkbox" name="estilos[]"
                                    value="{{ $estilo->id_estilo }}" id="estilo{{ $estilo->id_estilo }}"
                                    {{ is_array(request('estilos')) && in_array($estilo->id_estilo, request('estilos')) ? 'checked' : '' }}>
                                <label for="estilo{{ $estilo->id_estilo }}">{{ $estilo->nombre_estilo }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Filtro por precio -->
                    <div class="grupo-filtro">
                        <label>Precio medio</label>
                        <div class="filtro-fila-precio">
                            <input type="number" name="precio_min" class="filtro-campo" placeholder="Min €"
                                value="{{ request('precio_min') }}">
                            <span class="filtro-separador">—</span>
                            <input type="number" name="precio_max" class="filtro-campo" placeholder="Max €"
                                value="{{ request('precio_max') }}">
                        </div>
                    </div>

                    <!-- Filtro por valoracion -->
                    <div class="grupo-filtro">
                        <label>Valoración mínima</label>
                        @for($i = 1; $i <= 5; $i++)
                            <div class="filtro-radio">
                                <input type="radio" name="valoracion_min"
                                    value="{{ $i }}" id="val{{ $i }}"
                                    {{ request('valoracion_min') == $i ? 'checked' : '' }}>
                                <label for="val{{ $i }}">
                                    @for($j = 1; $j <= $i; $j++)
                                        <i class="bi bi-star-fill icono-estrella"></i>
                                    @endfor
                                    <span>y más</span>
                                </label>
                            </div>
                        @endfor
                    </div>

                    <!-- Ordenar por -->
                    <div class="grupo-filtro">
                        <label>Ordenar por</label>
                        <select name="orden" class="filtro-selector" onchange="this.form.submit()">
                            <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="valoracion" {{ request('orden') == 'valoracion' ? 'selected' : '' }}>Valoración</option>
                            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio ↑</option>
                            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio ↓</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-filtrar">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="{{ route('restaurantes.index') }}" class="btn-limpiar">
                        <i class="bi bi-x-circle"></i> Limpiar filtros
                    </a>
                </form>
            </aside>
            <!-- Contenido principal: listado de restaurantes -->
            <section class="contenido-restaurantes">
                <p class="contador-resultados">{{ $restaurantes->total() }} restaurantes encontrados</p>

                @if($restaurantes->isEmpty())
                    <div class="sin-resultados">
                        <i class="bi bi-search" style="font-size: 48px;"></i>
                        <h3>Sin resultados</h3>
                        <p>No se encontraron restaurantes con esos filtros.</p>
                    </div>
                @else
                    <div class="cuadricula-restaurantes">
                        @foreach($restaurantes as $restaurante)
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
                                        <div class="tarjeta-info">
                                            <span class="cocina">
                                                @foreach($restaurante->estilos as $estilo)
                                                    {{ $estilo->nombre_estilo }}@if(!$loop->last), @endif
                                                @endforeach
                                            </span>
                                        </div>
                                        <div class="tarjeta-meta">
                                            <span class="ubicacion">
                                                <i class="bi bi-geo-alt-fill"></i> {{ $restaurante->ciudad->nombre_ciudad ?? 'Sin ciudad' }}
                                            </span>
                                            @if($restaurante->precio_restaurante)
                                                <span class="precio">{{ number_format($restaurante->precio_restaurante, 0) }}€</span>
                                            @endif
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

                    <!-- Paginacion -->
                    <div class="paginacion">
                        {{ $restaurantes->links() }}
                    </div>
                @endif
            </section>

        </div>
    </div>
</div>
@endsection
