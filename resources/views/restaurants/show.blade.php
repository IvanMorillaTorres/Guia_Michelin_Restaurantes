@extends('layouts.app')

@section('title', $restaurant->nombre . ' - Guía Michelin')

@section('content')
<div class="restaurant-detail">
    <!-- Hero Image -->
    <section class="detail-hero">
        @if($restaurant->mainImage)
            <img src="{{ $restaurant->mainImage->url }}" alt="{{ $restaurant->nombre }}">
        @else
            <div class="hero-placeholder">🍽️</div>
        @endif
        
        <!-- Overlay Info -->
        <div class="hero-overlay">
            <div class="container">
                <div class="hero-badges">
                    @if($restaurant->estrellas_michelin > 0)
                        <span class="badge-large michelin">
                            @for($i = 0; $i < $restaurant->estrellas_michelin; $i++) ⭐ @endfor
                            {{ $restaurant->estrellas_michelin }} {{ $restaurant->estrellas_michelin == 1 ? 'Estrella' : 'Estrellas' }} Michelin
                        </span>
                    @endif
                    @if($restaurant->bib_gourmand)
                        <span class="badge-large bib">🍴 Bib Gourmand</span>
                    @endif
                    @if($restaurant->estrella_verde)
                        <span class="badge-large green">🌿 Estrella Verde</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="detail-layout">
            <!-- Main Content -->
            <div class="detail-main">
                <header class="detail-header">
                    <h1>{{ $restaurant->nombre }}</h1>
                    
                    <div class="detail-meta">
                        <span class="cuisine-type">
                            {{ $restaurant->cuisineTypes->pluck('nombre')->implode(' • ') }}
                        </span>
                        <span class="price-range">{{ $restaurant->rango_precios }}</span>
                    </div>

                    @if($restaurant->valoracion > 0)
                        <div class="rating-large">
                            <span class="stars">⭐ {{ number_format($restaurant->valoracion, 1) }}</span>
                            <span class="rating-text">Valoración</span>
                        </div>
                    @endif
                </header>

                <!-- Descripción -->
                @if($restaurant->descripcion)
                    <section class="detail-section">
                        <h2>Sobre el restaurante</h2>
                        <p class="description">{{ $restaurant->descripcion }}</p>
                    </section>
                @endif

                <!-- Chef -->
                @if($restaurant->chef)
                    <section class="detail-section">
                        <h3>👨‍🍳 Chef</h3>
                        <p><strong>{{ $restaurant->chef }}</strong></p>
                    </section>
                @endif

                <!-- Galería de imágenes -->
                @if($restaurant->images->count() > 0)
                    <section class="detail-section">
                        <h2>Galería</h2>
                        <div class="image-gallery">
                            @foreach($restaurant->images as $image)
                                <img src="{{ $image->url }}" alt="{{ $image->alt_text ?? $restaurant->nombre }}">
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Categorías -->
                @if($restaurant->categories->count() > 0)
                    <section class="detail-section">
                        <h3>Características</h3>
                        <div class="tags">
                            @foreach($restaurant->categories as $category)
                                <span class="tag">{{ $category->nombre }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Facilidades -->
                <section class="detail-section">
                    <h3>Facilidades</h3>
                    <div class="facilities">
                        @if($restaurant->terraza)
                            <span class="facility">🌤️ Terraza</span>
                        @endif
                        @if($restaurant->parking)
                            <span class="facility">🅿️ Parking</span>
                        @endif
                        @if($restaurant->accesible)
                            <span class="facility">♿ Accesible</span>
                        @endif
                    </div>
                </section>
            </div>

            <!-- Sidebar Info -->
            <aside class="detail-sidebar">
                <div class="info-card">
                    <h3>Información de contacto</h3>
                    
                    <div class="info-item">
                        <strong>📍 Dirección</strong>
                        <p>{{ $restaurant->direccion }}</p>
                        <p>{{ $restaurant->codigo_postal }} {{ $restaurant->city->nombre }}</p>
                    </div>

                    @if($restaurant->telefono)
                        <div class="info-item">
                            <strong>📞 Teléfono</strong>
                            <p><a href="tel:{{ $restaurant->telefono }}">{{ $restaurant->telefono }}</a></p>
                        </div>
                    @endif

                    @if($restaurant->website)
                        <div class="info-item">
                            <strong>🌐 Sitio web</strong>
                            <p><a href="{{ $restaurant->website }}" target="_blank">Visitar web</a></p>
                        </div>
                    @endif

                    @if($restaurant->horario)
                        <div class="info-item">
                            <strong>🕐 Horario</strong>
                            <p>{{ $restaurant->horario }}</p>
                            @if($restaurant->dia_cierre)
                                <p class="closed-day">Cerrado: {{ $restaurant->dia_cierre }}</p>
                            @endif
                        </div>
                    @endif

                    @if($restaurant->precio_medio)
                        <div class="info-item price-info">
                            <strong>💰 Precio medio</strong>
                            <p class="price-large">{{ number_format($restaurant->precio_medio, 0) }}€</p>
                            <small>por persona</small>
                        </div>
                    @endif

                    <button class="btn-reserve">Reservar mesa</button>
                </div>

                <!-- Mapa (placeholder) -->
                <div class="map-placeholder">
                    <p>📍 Ver en mapa</p>
                </div>
            </aside>
        </div>

        <!-- Restaurantes similares -->
        @if($similarRestaurants->count() > 0)
            <section class="similar-restaurants">
                <h2>Restaurantes similares</h2>
                <div class="restaurants-grid">
                    @foreach($similarRestaurants as $similar)
                        <article class="restaurant-card">
                            <a href="{{ route('restaurants.show', $similar->slug) }}" class="card-link">
                                <div class="card-image">
                                    @if($similar->mainImage)
                                        <img src="{{ $similar->mainImage->url }}" alt="{{ $similar->nombre }}">
                                    @else
                                        <div class="placeholder-image"><span>🍽️</span></div>
                                    @endif
                                    
                                    @if($similar->estrellas_michelin > 0)
                                        <div class="badge michelin-stars">
                                            @for($i = 0; $i < $similar->estrellas_michelin; $i++) ⭐ @endfor
                                        </div>
                                    @endif
                                </div>

                                <div class="card-content">
                                    <h3>{{ $similar->nombre }}</h3>
                                    <div class="card-info">
                                        <span class="cuisine">{{ $similar->cuisineTypes->pluck('nombre')->first() }}</span>
                                    </div>
                                    <div class="card-meta">
                                        <span class="location">📍 {{ $similar->city->nombre }}</span>
                                        <span class="price">{{ $similar->rango_precios }}</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
