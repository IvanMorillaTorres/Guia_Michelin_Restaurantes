@extends('layouts.app')

@section('titulo', 'Restaurantes')

@section('contenido')
<div class="container">
    <h1 class="my-4">Restaurantes</h1>
    <p class="text-muted">{{ $restaurantes->total() }} restaurantes encontrados</p>

    <div class="row">
        <!-- Filtros en la izquierda -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('restaurantes.index') }}" id="formularioFiltros">

                        <!-- Filtro por ciudad -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ciudad</label>
                            <select name="ciudad" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas</option>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id_ciudad }}" {{ request('ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                                        {{ $ciudad->nombre_ciudad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por estilo de cocina -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Estilo de cocina</label>
                            @foreach($estilos as $estilo)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estilos[]"
                                        value="{{ $estilo->id_estilo }}" id="estilo{{ $estilo->id_estilo }}"
                                        {{ is_array(request('estilos')) && in_array($estilo->id_estilo, request('estilos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estilo{{ $estilo->id_estilo }}">
                                        {{ $estilo->nombre_estilo }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Filtro por precio -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Precio</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="number" name="precio_min" class="form-control" placeholder="Min €"
                                        value="{{ request('precio_min') }}">
                                </div>
                                <div class="col">
                                    <input type="number" name="precio_max" class="form-control" placeholder="Max €"
                                        value="{{ request('precio_max') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Filtro por valoracion -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Valoración mínima</label>
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="valoracion_min"
                                        value="{{ $i }}" id="val{{ $i }}"
                                        {{ request('valoracion_min') == $i ? 'checked' : '' }}>
                                    <label class="form-check-label" for="val{{ $i }}">
                                        @for($j = 1; $j <= $i; $j++)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @endfor
                                        y más
                                    </label>
                                </div>
                            @endfor
                        </div>

                        <!-- Ordenar por -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ordenar por</label>
                            <select name="orden" class="form-select" onchange="this.form.submit()">
                                <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                                <option value="valoracion" {{ request('orden') == 'valoracion' ? 'selected' : '' }}>Valoración</option>
                                <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio (menor a mayor)</option>
                                <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio (mayor a menor)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mb-2">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="{{ route('restaurantes.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de restaurantes -->
        <div class="col-md-9">
            @if($restaurantes->isEmpty())
                <div class="alert alert-info">
                    No se encontraron restaurantes con esos filtros.
                </div>
            @else
                <div class="row">
                    @foreach($restaurantes as $restaurante)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <!-- Imagen del restaurante -->
                                @if($restaurante->imagenPrincipal)
                                    <img src="{{ $restaurante->imagenPrincipal->url }}"
                                        class="card-img-top" alt="{{ $restaurante->nombre_restaurante }}"
                                        style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <h5 class="card-title">{{ $restaurante->nombre_restaurante }}</h5>

                                    <!-- Estilos de cocina -->
                                    <p class="card-text">
                                        @foreach($restaurante->estilos as $estilo)
                                            <span class="badge bg-secondary">{{ $estilo->nombre_estilo }}</span>
                                        @endforeach
                                    </p>

                                    <!-- Ciudad y precio -->
                                    <p class="card-text">
                                        <i class="bi bi-geo-alt"></i> {{ $restaurante->ciudad->nombre_ciudad ?? 'Sin ciudad' }}
                                        @if($restaurante->precio_restaurante)
                                            <span class="float-end fw-bold">{{ number_format($restaurante->precio_restaurante, 0) }}€</span>
                                        @endif
                                    </p>

                                    <!-- Estrellas de valoracion con Bootstrap Icons -->
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($restaurante->valoracion_restaurante))
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @elseif($i - 0.5 <= $restaurante->valoracion_restaurante)
                                                <i class="bi bi-star-half text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <small class="text-muted">({{ number_format($restaurante->valoracion_restaurante, 1) }})</small>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <a href="{{ route('restaurantes.mostrar', $restaurante->slug) }}" class="btn btn-outline-dark btn-sm w-100">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginacion -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $restaurantes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
