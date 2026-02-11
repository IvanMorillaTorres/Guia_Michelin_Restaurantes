<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Panel Admin - Guía MICHELIN')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ @filemtime(public_path('css/estilos.css')) }}">
</head>
<body>

    <!-- Cabecera admin (misma que la web) -->
    <header class="cabecera">
        <div class="cabecera-ancho">
            <div class="cabecera-contenido">
                <a href="{{ route('admin.restaurantes.index') }}" class="logo">
                    <span class="logo-texto">GUÍA MICHELIN</span>
                    <span class="logo-subtexto">Panel de Administración</span>
                </a>
                <div class="cabecera-derecha">
                    <nav class="nav-principal">
                        <a href="{{ route('admin.restaurantes.index') }}" class="activo">
                            <i class="bi bi-shop"></i> Restaurantes
                        </a>
                        <a href="{{ route('restaurantes.index') }}">
                            <i class="bi bi-eye"></i> Ver web
                        </a>
                    </nav>
                    <div class="cabecera-acciones">
                        <button id="btn-dark-mode" class="icono-menu" title="Modo oscuro">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                        <span class="icono-menu" style="cursor:default;" title="{{ Auth::user()->nombre }}">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="icono-menu" title="Cerrar sesión">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido admin -->
    <main class="contenido-admin">
        <div class="container-admin">
            @if(session('exito'))
                <div class="alerta-exito">
                    <i class="bi bi-check-circle"></i> {{ session('exito') }}
                </div>
            @endif

            @yield('contenido')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function initDarkMode() {
            const htmlElement = document.documentElement;
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (isDarkMode || (!localStorage.getItem('darkMode') && prefersDark)) {
                htmlElement.setAttribute('data-theme', 'dark');
                updateButtonIcon(true);
            }
        }

        function updateButtonIcon(isDark) {
            const btn = document.getElementById('btn-dark-mode');
            if (btn) {
                btn.innerHTML = isDark ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-stars"></i>';
            }
        }

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
