<?php

use App\Http\Controllers\RestauranteController;
use Illuminate\Support\Facades\Route;

// pagina principal - muestra los restaurantes
Route::get('/', [RestauranteController::class, 'index'])->name('restaurantes.index');

// listado de restaurantes
Route::get('/restaurantes', [RestauranteController::class, 'index'])->name('restaurantes.listado');

// ver un restaurante en detalle
Route::get('/restaurante/{slug}', [RestauranteController::class, 'mostrar'])->name('restaurantes.mostrar');
