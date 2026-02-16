<?php

use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminRestauranteController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

// --- autenticacion (publica) ---
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- rutas protegidas (requieren login) ---
Route::middleware('auth')->group(function () {

    // pagina principal - muestra los restaurantes
    Route::get('/', [RestauranteController::class, 'index'])->name('restaurantes.index');

    // listado de restaurantes
    Route::get('/restaurantes', [RestauranteController::class, 'index'])->name('restaurantes.listado');

    // ver un restaurante en detalle
    Route::get('/restaurante/{slug}', [RestauranteController::class, 'mostrar'])->name('restaurantes.mostrar');

    // valorar un restaurante
    Route::post('/restaurante/{slug}/valorar', [RestauranteController::class, 'valorar'])->name('restaurantes.valorar');

    // comentar un restaurante
    Route::post('/restaurante/{slug}/comentar', [RestauranteController::class, 'comentar'])->name('restaurantes.comentar');

    // guardar/quitar restaurante
    Route::post('/restaurante/{slug}/guardar', [RestauranteController::class, 'toggleGuardado'])->name('restaurantes.guardar');

    // perfil
    Route::get('/perfil', [PerfilController::class, 'mostrar'])->name('perfil');

    // actualizar datos del perfil
    Route::put('/perfil', [PerfilController::class, 'actualizarDatos'])->name('perfil.actualizar');

    // cambiar contraseña
    Route::put('/perfil/password', [PerfilController::class, 'actualizarPassword'])->name('perfil.password');
});

// Endpoints pequeños para selects dependientes (paises -> comunidades -> ciudades)
Route::middleware('auth')->group(function () {
    Route::get('/api/paises/{id}/comunidades', function ($id) {
        return \App\Models\Comunidad::where('id_pais', $id)->orderBy('nombre_comunidad')->get();
    })->name('api.paises.comunidades');

    Route::get('/api/comunidades/{id}/ciudades', function ($id) {
        return \App\Models\Ciudad::where('id_comunidad', $id)->orderBy('nombre_ciudad')->get();
    })->name('api.comunidades.ciudades');
});

// --- panel de administracion (solo admin) ---
Route::prefix('admin')->middleware(['auth', 'esAdmin'])->group(function () {
    // CRUD de Restaurantes
    Route::get('/restaurantes', [AdminRestauranteController::class, 'index'])->name('admin.restaurantes.index');
    Route::get('/restaurantes/crear', [AdminRestauranteController::class, 'crear'])->name('admin.restaurantes.crear');
    Route::post('/restaurantes', [AdminRestauranteController::class, 'guardar'])->name('admin.restaurantes.guardar');
    Route::get('/restaurantes/{id}/editar', [AdminRestauranteController::class, 'editar'])->name('admin.restaurantes.editar');
    Route::put('/restaurantes/{id}', [AdminRestauranteController::class, 'actualizar'])->name('admin.restaurantes.actualizar');
    Route::delete('/restaurantes/{id}', [AdminRestauranteController::class, 'eliminar'])->name('admin.restaurantes.eliminar');

    // CRUD de Usuarios
    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/usuarios/crear', [AdminUserController::class, 'crear'])->name('admin.usuarios.crear');
    Route::post('/usuarios', [AdminUserController::class, 'guardar'])->name('admin.usuarios.guardar');
    Route::get('/usuarios/{id}/editar', [AdminUserController::class, 'editar'])->name('admin.usuarios.editar');
    Route::put('/usuarios/{id}', [AdminUserController::class, 'actualizar'])->name('admin.usuarios.actualizar');
    Route::delete('/usuarios/{id}', [AdminUserController::class, 'eliminar'])->name('admin.usuarios.eliminar');
});
