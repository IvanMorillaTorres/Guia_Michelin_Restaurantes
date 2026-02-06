@extends('layouts.app')

@section('title', 'Inicio - Guía Michelin Catalunya')

@section('content')
<div class="home-page">
    <!-- Hero principal -->
    <section class="home-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Descubre la Excelencia Gastronómica</h1>
                <p>Explora los mejores restaurantes de Catalunya con Estrellas Michelin</p>
                <a href="{{ route('restaurants.index') }}" class="btn-explore">
                    Explorar Restaurantes
                </a>
            </div>
        </div>
    </section>

    <!-- Destacados -->
    <section class="featured-section">
        <div class="container">
            <h2>Distinciones Michelin</h2>
            <div class="distinctions-grid">
                <a href="{{ route('restaurants.index', ['stars' => 3]) }}" class="distinction-card">
                    <div class="distinction-icon">⭐⭐⭐</div>
                    <h3>Tres Estrellas</h3>
                    <p>Cocina excepcional que merece el viaje</p>
                </a>

                <a href="{{ route('restaurants.index', ['stars' => 2]) }}" class="distinction-card">
                    <div class="distinction-icon">⭐⭐</div>
                    <h3>Dos Estrellas</h3>
                    <p>Cocina excelente que merece un desvío</p>
                </a>

                <a href="{{ route('restaurants.index', ['stars' => 1]) }}" class="distinction-card">
                    <div class="distinction-icon">⭐</div>
                    <h3>Una Estrella</h3>
                    <p>Cocina de gran calidad</p>
                </a>

                <a href="{{ route('restaurants.index', ['stars' => 'bib']) }}" class="distinction-card">
                    <div class="distinction-icon">🍴</div>
                    <h3>Bib Gourmand</h3>
                    <p>La mejor relación calidad-precio</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Ciudades -->
    <section class="cities-section">
        <div class="container">
            <h2>Explora por Ciudad</h2>
            <div class="cities-grid">
                <a href="{{ route('restaurants.index', ['city' => 1]) }}" class="city-card">
                    <div class="city-name">Barcelona</div>
                </a>
                <a href="{{ route('restaurants.index', ['city' => 2]) }}" class="city-card">
                    <div class="city-name">Girona</div>
                </a>
            </div>
        </div>
    </section>
</div>

<style>
.home-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #d71921 100%);
    color: white;
    padding: 120px 0;
    text-align: center;
}

.hero-content h1 {
    font-size: 56px;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-content p {
    font-size: 20px;
    margin-bottom: 40px;
    opacity: 0.95;
}

.btn-explore {
    display: inline-block;
    padding: 18px 40px;
    background: white;
    color: #d71921;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn-explore:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.featured-section,
.cities-section {
    padding: 80px 0;
}

.featured-section h2,
.cities-section h2 {
    font-size: 36px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 50px;
    color: #2c2c2c;
}

.distinctions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.distinction-card {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 40px 30px;
    text-align: center;
    transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
}

.distinction-card:hover {
    transform: translateY(-5px);
    border-color: #d71921;
    box-shadow: 0 10px 30px rgba(215, 25, 33, 0.1);
}

.distinction-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.distinction-card h3 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #1a1a1a;
}

.distinction-card p {
    font-size: 14px;
    color: #666;
}

.cities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.city-card {
    background: linear-gradient(135deg, rgba(26,26,26,0.8) 0%, rgba(215,25,33,0.8) 100%), 
                url('https://images.unsplash.com/photo-1583422409516-2895a77efded?w=800') center/cover;
    height: 200px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.city-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.city-name {
    font-size: 32px;
    font-weight: 700;
    color: white;
    text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
}
</style>
@endsection
