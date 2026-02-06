<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guía Michelin - Restaurantes')</title>
    <link rel="stylesheet" href="{{ asset('css/michelin.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>GUÍA MICHELIN</h1>
                    <p class="tagline">Restaurantes de Catalunya</p>
                </div>
                <nav class="main-nav">
                    <a href="{{ route('restaurants.index') }}" class="active">Restaurantes</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Guía Michelin</h4>
                    <p>La referencia gastronómica internacional</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces</h4>
                    <a href="#">Sobre nosotros</a>
                    <a href="#">Contacto</a>
                    <a href="#">Prensa</a>
                </div>
                <div class="footer-section">
                    <h4>Síguenos</h4>
                    <a href="#">Instagram</a>
                    <a href="#">Facebook</a>
                    <a href="#">Twitter</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Guía Michelin. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
