# Guía Michelin - Restaurantes

Proyecto de clase para el módulo **0616 - Proyecto de Desarrollo de Aplicaciones Web**.  
Básicamente es una web tipo guía gastronómica donde puedes buscar restaurantes, dejar valoraciones, comentarios y guardar tus favoritos. Tiene un panel de administración para gestionar todo el contenido.

> Hecho con **Laravel 12**, **MySQL**, **Blade** y **Vite**.

---

## ¿Qué tiene la app?

### Para usuarios registrados
- **Buscador de restaurantes** con filtros por país, comunidad autónoma, ciudad, estilo de cocina, rango de precios y valoración mínima.
- **Ficha detallada** de cada restaurante con su descripción, galería de imágenes, ubicación, precio medio, estilos de cocina, teléfono y enlace a su web real.
- **Sistema de valoraciones** — puedes puntuar un restaurante del 1 al 5 y la media se recalcula automáticamente.
- **Comentarios** — deja tu opinión con una puntuación sobre cada restaurante (uno por usuario).
- **Guardar restaurantes** — un botón para añadir/quitar restaurantes de tu lista de favoritos.
- **Perfil de usuario** — puedes editar tus datos personales y cambiar la contraseña.

### Panel de administración
- **CRUD completo de restaurantes** — crear, editar y borrar restaurantes con subida de múltiples imágenes, selects dependientes (país → comunidad → ciudad) y asignación de estilos de cocina.
- **CRUD de usuarios** — gestión de todos los usuarios de la plataforma (crear, editar, eliminar, asignar roles).
- **CRUD de estilos de cocina** — añadir, editar y borrar estilos (japonés, mediterráneo, fusión...).
- **Notificaciones por email** — cada vez que se crea, edita o elimina un restaurante se envía un correo de notificación.

---

## Tecnologías

| Tecnología | Versión / Detalle |
|---|---|
| PHP | >= 8.2 |
| Laravel | 12 |
| Base de datos | MySQL |
| Frontend | Blade + Vite |
| Servidor local | WAMP |

---

## Estructura del proyecto (lo más importante)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          → Login, registro y logout
│   │   ├── RestauranteController.php   → Listado, detalle, valorar, comentar, guardar
│   │   ├── PerfilController.php        → Editar perfil y contraseña
│   │   └── Admin/
│   │       ├── AdminRestauranteController.php  → CRUD restaurantes
│   │       ├── AdminUserController.php         → CRUD usuarios
│   │       └── AdminEstiloController.php       → CRUD estilos de cocina
│   └── Middleware/
│       └── EsAdmin.php                 → Protege el panel de admin
├── Mail/
│   └── NotificacionCrudRestaurante.php → Mailable para avisar del CRUD
└── Models/
    ├── User.php
    ├── Restaurante.php
    ├── Ciudad.php
    ├── Comunidad.php
    ├── Pais.php
    ├── Estilo.php
    ├── Imagen.php
    ├── Valoracion.php
    ├── Comentario.php
    └── Rol.php

resources/views/
├── welcome.blade.php          → Landing page
├── home.blade.php             → Página principal
├── perfil.blade.php           → Editar perfil
├── auth/                      → Login y registro
├── restaurantes/              → Listado y ficha de restaurante
├── admin/                     → Panel de administración
│   ├── restaurantes/          → Vistas CRUD restaurantes
│   ├── usuarios/              → Vistas CRUD usuarios
│   └── estilos/               → Vista CRUD estilos
├── emails/                    → Plantilla de correo
└── layouts/                   → Layouts base de Blade
```

---

## Base de datos

El esquema de la base de datos está definido en las migraciones de Laravel. Las tablas principales son:

| Tabla | Descripción |
|---|---|
| `users` | Usuarios de la plataforma |
| `roles` | Roles (admin, usuario...) |
| `restaurantes` | Los restaurantes de la guía |
| `paises` | Países |
| `comunidades` | Comunidades autónomas (ligadas a un país) |
| `ciudades` | Ciudades (ligadas a una comunidad) |
| `estilos` | Estilos de cocina (japonés, italiano...) |
| `rest_estilos` | Tabla pivot restaurante ↔ estilo (N:M) |
| `imagenes` | Imágenes de los restaurantes |
| `valoraciones` | Puntuaciones de usuarios a restaurantes |
| `comentarios` | Comentarios con puntuación |
| `restaurantes_guardados` | Favoritos de cada usuario |

---

## Instalación

1. **Clona el repositorio** en tu carpeta de WAMP (o donde tengas el servidor):
   ```bash
   git clone <url-del-repo> guia_michelin_restaurantes
   cd guia_michelin_restaurantes
   ```

2. **Instala las dependencias de PHP y Node**:
   ```bash
   composer install
   npm install
   ```

3. **Configura el `.env`**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Edita el `.env` y pon los datos de tu base de datos MySQL:
   ```env
   DB_DATABASE=guia_michelin_restaurantes
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Crea la base de datos** `guia_michelin_restaurantes` en phpMyAdmin (o por consola) y ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

5. **Arranca el servidor de desarrollo**:
   ```bash
   composer dev
   ```
   Esto lanza a la vez el servidor de Laravel, la cola de trabajos y Vite.

---

## Autor

**Iván Morilla Torres**  
Estudiante de DAW — Curso 2025/2026
