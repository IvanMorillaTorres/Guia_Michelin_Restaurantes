@extends('layouts.app')

@section('titulo', 'Perfil - Guía MICHELIN')

@section('contenido')
<div class="container" style="padding: 30px 0;">
    <h1 style="font-weight: 700;">Perfil</h1>
    <p style="color: var(--texto-claro); margin-bottom: 25px;">
        Hola, {{ $usuario->nombre }} {{ $usuario->apellido1 }}! Bienvenido a tu perfil. Aquí puedes ver tus restaurantes guardados y gestionar tu cuenta.
    </p>

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
@endsection
