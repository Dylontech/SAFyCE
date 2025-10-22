<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class GrupoController
 * @package App\Http\Controllers
 */
class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        verificarAutenticacion();
        
        $query = Grupo::query();

        // Aplicar filtro de búsqueda
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', '%' . $search . '%')
                  ->orWhere('semestre', 'like', '%' . $search . '%')
                  ->orWhere('letra', 'like', '%' . $search . '%');
            });
        }

        // Aplicar filtro por semestre
        if ($request->has('semestre') && !empty($request->input('semestre'))) {
            $query->porSemestre($request->input('semestre'));
        }

        // Aplicar filtro por estado
        if ($request->has('activo') && $request->input('activo') !== '') {
            $query->where('activo', $request->input('activo'));
        }

        $grupos = $query->orderBy('semestre')->orderBy('letra')->paginate(15);

        // Obtener semestres únicos para el filtro
        $semestres = Grupo::distinct()->orderBy('semestre')->pluck('semestre');

        return view('grupo.index', compact('grupos', 'semestres'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        verificarAutenticacion();
        
        $grupo = new Grupo();
        return view('grupo.create', compact('grupo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        verificarAutenticacion();

        $validator = Validator::make($request->all(), [
            'semestre' => 'required|integer|min:1|max:12',
            'letra' => 'required|string|max:10',
            'activo' => 'boolean'
        ], [
            'semestre.required' => 'El semestre es obligatorio',
            'semestre.integer' => 'El semestre debe ser un número entero',
            'semestre.min' => 'El semestre debe ser al menos 1',
            'semestre.max' => 'El semestre no puede ser mayor a 12',
            'letra.required' => 'La letra del grupo es obligatoria',
            'letra.max' => 'La letra no puede tener más de 10 caracteres'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        try {
            // Generar nombre completo automáticamente
            $nombreCompleto = $request->semestre . strtolower($request->letra);
            
            // Verificar que no exista ya este grupo
            $existeGrupo = Grupo::where('nombre_completo', $nombreCompleto)->first();
            if ($existeGrupo) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ya existe un grupo con el nombre "' . $nombreCompleto . '"');
            }

            $grupo = new Grupo([
                'semestre' => $request->semestre,
                'letra' => strtolower($request->letra),
                'nombre_completo' => $nombreCompleto,
                'activo' => $request->has('activo') ? true : false
            ]);

            $grupo->save();

            return redirect()->route('grupos.index')
                ->with('success', 'Grupo creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        verificarAutenticacion();
        
        $grupo = Grupo::find($id);
        
        if (!$grupo) {
            return redirect()->route('grupos.index')
                ->with('error', 'Grupo no encontrado.');
        }

        // Obtener alumnos del grupo
        $alumnos = $grupo->alumnos()->paginate(15);

        return view('grupo.show', compact('grupo', 'alumnos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        verificarAutenticacion();
        
        $grupo = Grupo::find($id);
        
        if (!$grupo) {
            return redirect()->route('grupos.index')
                ->with('error', 'Grupo no encontrado.');
        }

        return view('grupo.edit', compact('grupo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        verificarAutenticacion();

        $grupo = Grupo::find($id);
        
        if (!$grupo) {
            return redirect()->route('grupos.index')
                ->with('error', 'Grupo no encontrado.');
        }

        $validator = Validator::make($request->all(), [
            'semestre' => 'required|integer|min:1|max:12',
            'letra' => 'required|string|max:10',
            'activo' => 'boolean'
        ], [
            'semestre.required' => 'El semestre es obligatorio',
            'semestre.integer' => 'El semestre debe ser un número entero',
            'semestre.min' => 'El semestre debe ser al menos 1',
            'semestre.max' => 'El semestre no puede ser mayor a 12',
            'letra.required' => 'La letra del grupo es obligatoria',
            'letra.max' => 'La letra no puede tener más de 10 caracteres'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        try {
            // Generar nombre completo automáticamente
            $nombreCompleto = $request->semestre . strtolower($request->letra);
            
            // Verificar que no exista ya este grupo (excluyendo el actual)
            $existeGrupo = Grupo::where('nombre_completo', $nombreCompleto)
                                ->where('id', '!=', $id)
                                ->first();
            if ($existeGrupo) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ya existe un grupo con el nombre "' . $nombreCompleto . '"');
            }

            $grupo->update([
                'semestre' => $request->semestre,
                'letra' => strtolower($request->letra),
                'nombre_completo' => $nombreCompleto,
                'activo' => $request->has('activo') ? true : false
            ]);

            return redirect()->route('grupos.index')
                ->with('success', 'Grupo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        verificarAutenticacion();
        
        try {
            $grupo = Grupo::find($id);
            
            if (!$grupo) {
                return redirect()->route('grupos.index')
                    ->with('error', 'Grupo no encontrado.');
            }

            // Verificar si hay alumnos asignados a este grupo
            $alumnosCount = $grupo->alumnos()->count();
            if ($alumnosCount > 0) {
                return redirect()->route('grupos.index')
                    ->with('error', 'No se puede eliminar el grupo porque tiene ' . $alumnosCount . ' alumno(s) asignado(s).');
            }

            $grupo->delete();

            return redirect()->route('grupos.index')
                ->with('success', 'Grupo eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('grupos.index')
                ->with('error', 'Error al eliminar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Obtener grupos por semestre (AJAX)
     */
    public function getGruposPorSemestre($semestre)
    {
        if (!$semestre) {
            return response()->json([]);
        }

        try {
            $grupos = Grupo::activos()
                          ->porSemestre($semestre)
                          ->orderBy('letra')
                          ->get(['id', 'nombre_completo', 'letra']);

            return response()->json($grupos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar grupos'], 500);
        }
    }
}
