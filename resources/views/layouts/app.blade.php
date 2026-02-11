<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Guía MICHELIN - Restaurantes')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Nuestros estilos -->
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ @filemtime(public_path('css/estilos.css')) }}">
</head>
<body>

    <!-- Cabecera estilo Michelin -->
    <header class="cabecera">
        <div class="cabecera-ancho">
            <div class="cabecera-contenido">
                <a href="{{ route('restaurantes.index') }}" class="logo">
                    <span class="logo-texto">GUÍA MICHELIN</span>
                </a>
                <div class="cabecera-derecha">
                    <nav class="nav-principal">
                        <a href="{{ route('restaurantes.index') }}" class="activo">Restaurantes</a>
                    </nav>
                    <div class="cabecera-acciones">
                        @auth
                            @if(Auth::user()->id_rol == 1)
                                <a href="{{ route('admin.restaurantes.index') }}" class="btn-panel-admin" title="Panel Admin">
                                    <i class="bi bi-gear"></i> Admin
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="icono-menu" title="Cerrar sesión">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="icono-usuario" title="Iniciar sesión">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        @endauth
                        <button class="icono-menu" title="Menú"><i class="bi bi-list"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main>
        @yield('contenido')
    </main>

    <!-- Pie de pagina estilo Michelin -->
    <footer class="pie-pagina">
        <div class="container">
            <div class="pie-contenido">
                <div class="pie-seccion">
                    <h4>Guía MICHELIN</h4>
                    <p>La referencia gastronómica desde 1900</p>
                    <p>Selección de los mejores restaurantes</p>
                </div>
                <div class="pie-seccion">
                    <h4>Selección</h4>
                    <a href="{{ route('restaurantes.index') }}">Todos los restaurantes</a>
                    <a href="{{ route('restaurantes.index', ['orden' => 'valoracion']) }}">Mejor valorados</a>
                </div>
                <div class="pie-seccion">
                    <h4>Sobre nosotros</h4>
                    <p>Proyecto académico</p>
                    <p>DAW - 2026</p>
                </div>
            </div>
            <div class="pie-inferior">
                <p>&copy; 2026 Guía MICHELIN Restaurantes &middot; Proyecto educativo</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
