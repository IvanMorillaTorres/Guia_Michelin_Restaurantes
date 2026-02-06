<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\City;
use App\Models\CuisineType;
use App\Models\Category;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Mostrar listado de restaurantes con filtros
     */
    public function index(Request $request)
    {
        $query = Restaurant::with(['city', 'cuisineTypes', 'categories', 'mainImage'])
            ->active();

        // Filtro por ciudad
        if ($request->has('city') && $request->city != '') {
            $query->where('city_id', $request->city);
        }

        // Filtro por estrellas Michelin
        if ($request->has('stars') && $request->stars != '') {
            if ($request->stars == 'bib') {
                $query->where('bib_gourmand', true);
            } else {
                $query->where('estrellas_michelin', $request->stars);
            }
        }

        // Filtro por tipo de cocina
        if ($request->has('cuisine') && $request->cuisine != '') {
            $query->whereHas('cuisineTypes', function($q) use ($request) {
                $q->where('cuisine_type_id', $request->cuisine);
            });
        }

        // Filtro por categoría
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Filtro por rango de precio
        if ($request->has('price') && $request->price != '') {
            $query->where('rango_precios', $request->price);
        }

        // Ordenamiento
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'stars':
                $query->orderBy('estrellas_michelin', 'desc');
                break;
            case 'price-asc':
                $query->orderBy('precio_medio', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('precio_medio', 'desc');
                break;
            case 'rating':
                $query->orderBy('valoracion', 'desc');
                break;
            case 'distance':
                // Si hay ciudad seleccionada, ordenar por distancia
                if ($request->has('city') && $request->city != '') {
                    $city = City::find($request->city);
                    if ($city) {
                        $query->orderByDistance($city->latitud, $city->longitud);
                    }
                }
                break;
            default: // 'name'
                $query->orderBy('nombre', 'asc');
                break;
        }

        $restaurants = $query->paginate(12)->withQueryString();

        // Datos para filtros
        $cities = City::orderBy('nombre')->get();
        $cuisineTypes = CuisineType::orderBy('nombre')->get();
        $categories = Category::orderBy('nombre')->get();

        return view('restaurants.index', compact(
            'restaurants',
            'cities',
            'cuisineTypes',
            'categories'
        ));
    }

    /**
     * Mostrar detalle de un restaurante
     */
    public function show($slug)
    {
        $restaurant = Restaurant::with([
            'city',
            'cuisineTypes',
            'categories',
            'images'
        ])->where('slug', $slug)->firstOrFail();

        // Restaurantes similares (misma ciudad y tipo de cocina)
        $similarRestaurants = Restaurant::with(['city', 'cuisineTypes', 'mainImage'])
            ->where('id', '!=', $restaurant->id)
            ->where('city_id', $restaurant->city_id)
            ->whereHas('cuisineTypes', function($q) use ($restaurant) {
                $q->whereIn('cuisine_type_id', $restaurant->cuisineTypes->pluck('id'));
            })
            ->active()
            ->limit(4)
            ->get();

        return view('restaurants.show', compact('restaurant', 'similarRestaurants'));
    }
}
