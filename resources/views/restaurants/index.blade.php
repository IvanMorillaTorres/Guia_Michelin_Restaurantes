@extends('layouts.app')

@section('title', 'Restaurantes - Guía Michelin Catalunya')

@section('content')
<div class="restaurants-page">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Descubre los mejores restaurantes de Catalunya</h1>
            <p>{{ $restaurants->total() }} restaurantes encontrados</p>
        </div>
    </section>

    <div class="container">
        <div class="restaurants-layout">
            <!-- Sidebar Filters -->
            <aside class="filters-sidebar">
                <h3>Filtrar por</h3>
                
                <form method="GET" action="{{ route('restaurants.index') }}" id="filterForm">
                    <!-- Ciudad -->
                    <div class="filter-group">
                        <label>Ciudad</label>
                        <select name="city" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Todas las ciudades</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estrellas Michelin -->
                    <div class="filter-group">
                        <label>Distinción</label>
                        <select name="stars" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Todas</option>
                            <option value="3" {{ request('stars') == '3' ? 'selected' : '' }}>⭐⭐⭐ Tres estrellas</option>
                            <option value="2" {{ request('stars') == '2' ? 'selected' : '' }}>⭐⭐ Dos estrellas</option>
                            <option value="1" {{ request('stars') == '1' ? 'selected' : '' }}>⭐ Una estrella</option>
                            <option value="bib" {{ request('stars') == 'bib' ? 'selected' : '' }}>🍴 Bib Gourmand</option>
                        </select>
                    </div>

                    <!-- Tipo de cocina -->
                    <div class="filter-group">
                        <label>Tipo de cocina</label>
                        <select name="cuisine" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Todas</option>
                            @foreach($cuisineTypes as $cuisine)
                                <option value="{{ $cuisine->id }}" {{ request('cuisine') == $cuisine->id ? 'selected' : '' }}>
                                    {{ $cuisine->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Categoría -->
                    <div class="filter-group">
                        <label>Categoría</label>
                        <select name="category" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Todas</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Precio -->
                    <div class="filter-group">
                        <label>Rango de precio</label>
                        <select name="price" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Todos</option>
                            <option value="€" {{ request('price') == '€' ? 'selected' : '' }}>€ Económico</option>
                            <option value="€€" {{ request('price') == '€€' ? 'selected' : '' }}>€€ Moderado</option>
                            <option value="€€€" {{ request('price') == '€€€' ? 'selected' : '' }}>€€€ Caro</option>
                            <option value="€€€€" {{ request('price') == '€€€€' ? 'selected' : '' }}>€€€€ Muy caro</option>
                        </select>
                    </div>

                    <!-- Ordenar -->
                    <div class="filter-group">
                        <label>Ordenar por</label>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
                            <option value="stars" {{ request('sort') == 'stars' ? 'selected' : '' }}>Estrellas</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Valoración</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Precio (menor)</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Precio (mayor)</option>
                            <option value="distance" {{ request('sort') == 'distance' ? 'selected' : '' }}>Distancia</option>
                        </select>
                    </div>

                    <button type="button" onclick="window.location='{{ route('restaurants.index') }}'" class="btn-reset">
                        Limpiar filtros
                    </button>
                </form>
            </aside>

            <!-- Restaurants Grid -->
            <div class="restaurants-content">
                @if($restaurants->isEmpty())
                    <div class="no-results">
                        <p>No se encontraron restaurantes con los filtros seleccionados.</p>
                    </div>
                @else
                    <div class="restaurants-grid">
                        @foreach($restaurants as $restaurant)
                            <article class="restaurant-card">
                                <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="card-link">
                                    <div class="card-image">
                                        @if($restaurant->mainImage)
                                            <img src="{{ $restaurant->mainImage->url }}" alt="{{ $restaurant->nombre }}">
                                        @else
                                            <div class="placeholder-image">
                                                <span>🍽️</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Distinción -->
                                        @if($restaurant->estrellas_michelin > 0)
                                            <div class="badge michelin-stars">
                                                @for($i = 0; $i < $restaurant->estrellas_michelin; $i++)
                                                    ⭐
                                                @endfor
                                            </div>
                                        @elseif($restaurant->bib_gourmand)
                                            <div class="badge bib-gourmand">🍴 Bib Gourmand</div>
                                        @endif

                                        @if($restaurant->estrella_verde)
                                            <div class="badge green-star">🌿 Sostenible</div>
                                        @endif
                                    </div>

                                    <div class="card-content">
                                        <h3>{{ $restaurant->nombre }}</h3>
                                        
                                        <div class="card-info">
                                            <span class="cuisine">
                                                {{ $restaurant->cuisineTypes->pluck('nombre')->implode(', ') }}
                                            </span>
                                        </div>

                                        <div class="card-meta">
                                            <span class="location">📍 {{ $restaurant->city->nombre }}</span>
                                            <span class="price">{{ $restaurant->rango_precios }}</span>
                                        </div>

                                        @if($restaurant->precio_medio)
                                            <div class="avg-price">
                                                Precio medio: {{ number_format($restaurant->precio_medio, 0) }}€
                                            </div>
                                        @endif

                                        @if($restaurant->valoracion > 0)
                                            <div class="rating">
                                                ⭐ {{ number_format($restaurant->valoracion, 1) }}/5.0
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="pagination">
                        {{ $restaurants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
