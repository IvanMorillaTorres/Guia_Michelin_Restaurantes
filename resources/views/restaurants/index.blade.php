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

                    <!-- Tipo de cocina (selección múltiple sumativa) -->
                    <div class="filter-group">
                        <label>Tipo de cocina</label>
                        <div class="checkbox-group">
                            @foreach($cuisineTypes as $cuisine)
                                <label class="checkbox-label">
                                    <input type="checkbox" name="cuisine[]" value="{{ $cuisine->id }}"
                                        {{ is_array(request('cuisine')) && in_array($cuisine->id, request('cuisine')) ? 'checked' : '' }}
                                        onchange="document.getElementById('filterForm').submit()">
                                    <span>{{ $cuisine->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
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

                    <!-- Precio medio de la carta -->
                    <div class="filter-group">
                        <label>Precio medio de la carta (€)</label>
                        <div class="range-inputs">
                            <input type="number" name="precio_min" placeholder="Mín" min="0" step="5"
                                value="{{ request('precio_min') }}"
                                class="range-input">
                            <span class="range-separator">—</span>
                            <input type="number" name="precio_max" placeholder="Màx" min="0" step="5"
                                value="{{ request('precio_max') }}"
                                class="range-input">
                        </div>
                    </div>

                    <!-- Valoración mínima -->
                    <div class="filter-group">
                        <label>Valoración mínima</label>
                        <div class="rating-filter">
                            @for($i = 5; $i >= 1; $i--)
                                <label class="radio-label">
                                    <input type="radio" name="valoracion_min" value="{{ $i }}"
                                        {{ request('valoracion_min') == $i ? 'checked' : '' }}
                                        onchange="document.getElementById('filterForm').submit()">
                                    <span class="stars-display">
                                        @for($j = 0; $j < $i; $j++) ⭐ @endfor
                                        <em>{{ $i }}.0+</em>
                                    </span>
                                </label>
                            @endfor
                            <label class="radio-label">
                                <input type="radio" name="valoracion_min" value=""
                                    {{ request('valoracion_min') == '' ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                <span>Totes</span>
                            </label>
                        </div>
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

                    <button type="submit" class="btn-apply">Aplicar filtros</button>

                    <button type="button" onclick="window.location='{{ route('restaurants.index') }}'" class="btn-reset">
                        Limpiar filtros
                    </button>

                    <!-- Filtros activos -->
                    @if(request()->anyFilled(['city', 'stars', 'cuisine', 'category', 'price', 'precio_min', 'precio_max', 'valoracion_min']))
                        <div class="active-filters">
                            <h4>Filtres actius:</h4>
                            @if(request('precio_min') || request('precio_max'))
                                <span class="filter-tag">
                                    Preu: {{ request('precio_min', '0') }}€ - {{ request('precio_max', '∞') }}€
                                </span>
                            @endif
                            @if(request('valoracion_min'))
                                <span class="filter-tag">
                                    Valoració: ≥ {{ request('valoracion_min') }}.0
                                </span>
                            @endif
                            @if(is_array(request('cuisine')))
                                @foreach($cuisineTypes->whereIn('id', request('cuisine')) as $ct)
                                    <span class="filter-tag">{{ $ct->nombre }}</span>
                                @endforeach
                            @endif
                        </div>
                    @endif
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
