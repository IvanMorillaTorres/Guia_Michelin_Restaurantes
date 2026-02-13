<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <button id="btn-dark-mode" class="icono-menu" title="Modo oscuro">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                        @auth
                            <a href="{{ route('perfil') }}" class="icono-usuario" title="Perfil">
                                <i class="bi bi-person-circle"></i>
                            </a>
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
    <!-- Dark Mode Script -->
    <script>
        // Detectar y aplicar el modo oscuro
        function initDarkMode() {
            const htmlElement = document.documentElement;
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Aplicar modo oscuro si está activo o si el sistema prefiere oscuro
            if (isDarkMode || (!localStorage.getItem('darkMode') && prefersDark)) {
                htmlElement.setAttribute('data-theme', 'dark');
                updateButtonIcon(true);
            }
        }

        // Actualizar icono del botón
        function updateButtonIcon(isDark) {
            const btn = document.getElementById('btn-dark-mode');
            if (btn) {
                btn.innerHTML = isDark ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-stars"></i>';
            }
        }

        // Toggle dark mode
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            
            const btn = document.getElementById('btn-dark-mode');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const htmlElement = document.documentElement;
                    const isDarkMode = htmlElement.getAttribute('data-theme') === 'dark';
                    
                    if (isDarkMode) {
                        htmlElement.removeAttribute('data-theme');
                        localStorage.setItem('darkMode', 'false');
                    } else {
                        htmlElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('darkMode', 'true');
                    }
                    
                    updateButtonIcon(!isDarkMode);
                });
            }
        });
    </script>
</body>
</html>
