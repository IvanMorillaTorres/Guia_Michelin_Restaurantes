<?php

use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'index'])->name('home');
Route::get('/restaurantes', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurante/{slug}', [RestaurantController::class, 'show'])->name('restaurants.show');
