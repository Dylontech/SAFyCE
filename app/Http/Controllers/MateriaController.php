<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Especialidade;
use Illuminate\Http\Request;

/**
 * Class MateriaController
 * @package App\Http\Controllers
 */
class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        verificarAutenticacion();
        $query = Materia::query();

        // Aplicar filtro de búsqueda
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('materia', 'like', '%' . $search . '%');
        }

        // Aplicar filtro por semestre
        if ($request->has('semestre') && !empty($request->input('semestre'))) {
            $query->where('semestre', $request->input('semestre'));
        }

        // Aplicar filtro por especialidad
        if ($request->has('especialidad') && !empty($request->input('especialidad'))) {
            $query->where('especialidad', $request->input('especialidad'));
        }

        $materias = $query->paginate(10)->appends($request->except('page'));
        
        // Cargar especialidades para los filtros
        $especialidades = Especialidade::orderBy('nombre')->get();
        
        return view('materia.index', compact('materias', 'especialidades'))
            ->with('i', (request()->input('page', 1) - 1) * $materias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        verificarAutenticacion();
        $materia = new Materia();
        $especialidades = Especialidade::orderBy('nombre')->get();
        return view('materia.create', compact('materia', 'especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        verificarAutenticacion();
        request()->validate(Materia::$rules);

        try {
            $materia = Materia::create($request->all());
            return redirect()->route('materias.index')
                ->with('success', 'Materia creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la materia: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        verificarAutenticacion();
        $materia = Materia::find($id);

        if (!$materia) {
            return redirect()->route('materias.index')
                ->with('error', 'Materia no encontrada.');
        }

        return view('materia.show', compact('materia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        verificarAutenticacion();
        $materia = Materia::find($id);

        if (!$materia) {
            return redirect()->route('materias.index')
                ->with('error', 'Materia no encontrada.');
        }

        $especialidades = Especialidade::orderBy('nombre')->get();
        return view('materia.edit', compact('materia', 'especialidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Materia $materia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Materia $materia)
    {
        verificarAutenticacion();
        request()->validate(Materia::$rules);

        try {
            $materia->update($request->all());
            return redirect()->route('materias.index')
                ->with('success', 'Materia actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la materia: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        verificarAutenticacion();
        
        try {
            $materia = Materia::find($id);
            
            if (!$materia) {
                return redirect()->route('materias.index')
                    ->with('error', 'Materia no encontrada.');
            }
            
            $materia->delete();
            
            return redirect()->route('materias.index')
                ->with('success', 'Materia eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('materias.index')
                ->with('error', 'Error al eliminar la materia: ' . $e->getMessage());
        }
    }
}
