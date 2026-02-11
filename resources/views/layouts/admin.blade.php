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

    <!-- Cabecera admin -->
    <header class="cabecera-admin">
        <div class="container">
            <div class="cabecera-admin-contenido">
                <a href="{{ route('admin.restaurantes.index') }}" class="logo-admin">
                    <span class="logo-texto">GUÍA MICHELIN</span>
                    <span class="logo-subtexto-admin">Panel de Administración</span>
                </a>
                <nav class="nav-admin">
                    <a href="{{ route('admin.restaurantes.index') }}" class="activo">
                        <i class="bi bi-shop"></i> Restaurantes
                    </a>
                    <a href="{{ route('restaurantes.index') }}">
                        <i class="bi bi-eye"></i> Ver web
                    </a>
                </nav>
                <div class="admin-usuario">
                    <span class="admin-nombre">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->nombre }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-cerrar-sesion">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido admin -->
    <main class="contenido-admin">
        <div class="container">
            @if(session('exito'))
                <div class="alerta-exito">
                    <i class="bi bi-check-circle"></i> {{ session('exito') }}
                </div>
            @endif

            @yield('contenido')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
