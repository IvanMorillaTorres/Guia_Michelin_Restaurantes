<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estilo;
use Illuminate\Http\Request;

class AdminEstiloController extends Controller
{
    // listado de estilos en el panel admin
    public function index(Request $request)
    {
        $consulta = Estilo::withCount('restaurantes'); // sacamos tambien cuantos restaurantes tiene cada estilo

        // busqueda por nombre
        if ($request->filled('busqueda')) {
            $consulta->where('nombre_estilo', 'like', "%{$request->busqueda}%");
        }

        // paginamos y mantenemos los filtros en los enlaces
        $estilos = $consulta->orderBy('nombre_estilo')->paginate(10)->withQueryString();

        return view('admin.estilos.index', compact('estilos'));
    }

    // guardar el estilo nuevo en la base de datos
    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'nombre_estilo'      => 'required|string|max:255|unique:estilos,nombre_estilo',
            'descripcion_estilo' => 'nullable|string|max:1000',
        ], [
            'nombre_estilo.required' => 'El nombre del estilo es obligatorio.',
            'nombre_estilo.unique'   => 'Ya existe un estilo con ese nombre.',
        ]);

        Estilo::create($datos); // guardamos el nuevo estilo en la BD

        return redirect()->route('admin.estilos.index')
            ->with('exito', 'Estilo de cocina creado correctamente.');
    }

    // actualizar el estilo en la base de datos
    public function actualizar(Request $request, $id)
    {
        $estilo = Estilo::findOrFail($id); // buscamos el estilo, si no existe salta 404

        $datos = $request->validate([
            'nombre_estilo'      => 'required|string|max:255|unique:estilos,nombre_estilo,' . $estilo->id_estilo . ',id_estilo',
            'descripcion_estilo' => 'nullable|string|max:1000',
        ], [
            'nombre_estilo.required' => 'El nombre del estilo es obligatorio.',
            'nombre_estilo.unique'   => 'Ya existe un estilo con ese nombre.',
        ]);

        $estilo->update($datos); // actualizamos en la BD

        return redirect()->route('admin.estilos.index')
            ->with('exito', 'Estilo de cocina actualizado correctamente.');
    }

    // eliminar un estilo
    public function eliminar($id)
    {
        $estilo = Estilo::withCount('restaurantes')->findOrFail($id);

        // proteger si tiene restaurantes asociados
        if ($estilo->restaurantes_count > 0) {
            return redirect()->route('admin.estilos.index')
                ->with('error', "No se puede eliminar «{$estilo->nombre_estilo}» porque tiene {$estilo->restaurantes_count} restaurante(s) asociado(s).");
        }

        $estilo->delete(); // lo borramos de la BD

        return redirect()->route('admin.estilos.index')
            ->with('exito', 'Estilo de cocina eliminado correctamente.');
    }
}
