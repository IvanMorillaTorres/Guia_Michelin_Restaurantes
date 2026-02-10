@extends('layouts.app')

@section('titulo', $restaurante->nombre_restaurante)

@section('contenido')
<div class="container">

    <!-- Boton para volver -->
    <a href="{{ route('restaurantes.index') }}" class="btn btn-outline-dark mb-3 mt-2">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>

    <div class="row">
        <!-- Columna principal -->
        <div class="col-md-8">
            <!-- Imagen principal -->
            @if($restaurante->imagenes->count() > 0)
                <img src="{{ $restaurante->imagenes->first()->url }}"
                    class="img-fluid rounded mb-4" alt="{{ $restaurante->nombre_restaurante }}"
                    style="width: 100%; max-height: 400px; object-fit: cover;">
            @endif

            <!-- Nombre del restaurante -->
            <h1>{{ $restaurante->nombre_restaurante }}</h1>

            <!-- Estrellas de valoracion con Bootstrap Icons -->
            <div class="mb-3">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($restaurante->valoracion_restaurante))
                        <i class="bi bi-star-fill text-warning fs-4"></i>
                    @elseif($i - 0.5 <= $restaurante->valoracion_restaurante)
                        <i class="bi bi-star-half text-warning fs-4"></i>
                    @else
                        <i class="bi bi-star text-warning fs-4"></i>
                    @endif
                @endfor
                <span class="ms-2 text-muted">({{ number_format($restaurante->valoracion_restaurante, 1) }} / 5.0)</span>
            </div>

            <!-- Estilos de cocina -->
            <div class="mb-3">
                @foreach($restaurante->estilos as $estilo)
                    <span class="badge bg-dark">{{ $estilo->nombre_estilo }}</span>
                @endforeach
            </div>

            <!-- Descripcion -->
            @if($restaurante->descripcion_restaurante)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Descripción</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $restaurante->descripcion_restaurante }}</p>
                    </div>
                </div>
            @endif

            <!-- Galeria de imagenes -->
            @if($restaurante->imagenes->count() > 1)
                <h4 class="mb-3">Galería</h4>
                <div class="row">
                    @foreach($restaurante->imagenes as $imagen)
                        <div class="col-md-4 mb-3">
                            <img src="{{ $imagen->url }}" class="img-fluid rounded" alt="{{ $restaurante->nombre_restaurante }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Columna lateral con informacion -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @if($restaurante->ciudad)
                        <li class="list-group-item">
                            <i class="bi bi-geo-alt"></i> <strong>Ciudad:</strong> {{ $restaurante->ciudad->nombre_ciudad }}
                        </li>
                    @endif
                    @if($restaurante->telefono_restaurante)
                        <li class="list-group-item">
                            <i class="bi bi-telephone"></i> <strong>Teléfono:</strong>
                            <a href="tel:{{ $restaurante->telefono_restaurante }}">{{ $restaurante->telefono_restaurante }}</a>
                        </li>
                    @endif
                    @if($restaurante->web_real_restaurante)
                        <li class="list-group-item">
                            <i class="bi bi-globe"></i> <strong>Web:</strong>
                            <a href="{{ $restaurante->web_real_restaurante }}" target="_blank">Visitar</a>
                        </li>
                    @endif
                    @if($restaurante->precio_restaurante)
                        <li class="list-group-item">
                            <i class="bi bi-cash"></i> <strong>Precio medio:</strong>
                            <span class="fw-bold text-success">{{ number_format($restaurante->precio_restaurante, 0) }}€</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Restaurantes parecidos -->
    @if($parecidos->count() > 0)
        <hr class="my-5">
        <h3>Restaurantes parecidos</h3>
        <div class="row mt-3">
            @foreach($parecidos as $parecido)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($parecido->imagenPrincipal)
                            <img src="{{ $parecido->imagenPrincipal->url }}"
                                class="card-img-top" alt="{{ $parecido->nombre_restaurante }}"
                                style="height: 150px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $parecido->nombre_restaurante }}</h6>
                            <!-- Estrellas -->
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($parecido->valoracion_restaurante))
                                        <i class="bi bi-star-fill text-warning small"></i>
                                    @elseif($i - 0.5 <= $parecido->valoracion_restaurante)
                                        <i class="bi bi-star-half text-warning small"></i>
                                    @else
                                        <i class="bi bi-star text-warning small"></i>
                                    @endif
                                @endfor
                            </div>
                            <a href="{{ route('restaurantes.mostrar', $parecido->slug) }}" class="btn btn-sm btn-outline-dark mt-2">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
