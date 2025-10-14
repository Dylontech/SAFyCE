<?php

namespace App\Http\Controllers\Maestros;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\Tarea;
use App\Models\Materia;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function index()
    {
        $maestroId = Auth::id();
        $calificaciones = Calificacion::where('maestro_id', $maestroId)
            ->with(['alumno', 'materia', 'tarea'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('maestros.calificaciones.index', compact('calificaciones'));
    }

    public function create()
    {
        $maestroId = Auth::id();
        $materias = Materia::whereHas('tareas', function($query) use ($maestroId) {
            $query->where('maestro_id', $maestroId);
        })->get();
        
        $tareas = Tarea::where('maestro_id', $maestroId)->where('estado', 'activa')->get();
        $alumnos = Alumno::all();
        
        return view('maestros.calificaciones.create', compact('materias', 'tareas', 'alumnos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'tarea_id' => 'nullable|exists:tareas,id',
            'materia_id' => 'required|exists:materias,id',
            'calificacion' => 'required|numeric|min:0|max:100',
            'tipo_evaluacion' => 'required|in:tarea,examen_parcial,examen_final,proyecto,participacion,practica',
            'periodo_escolar' => 'required|string|max:20',
            'parcial' => 'nullable|integer|min:1|max:3',
            'puntos_obtenidos' => 'nullable|integer|min:0',
            'puntos_totales' => 'nullable|integer|min:1',
            'comentarios' => 'nullable|string',
            'fecha_evaluacion' => 'required|date',
        ]);

        $validated['maestro_id'] = Auth::id();

        // Si se especifica una tarea, obtener los puntos totales de la tarea
        if ($validated['tarea_id']) {
            $tarea = Tarea::find($validated['tarea_id']);
            $validated['puntos_totales'] = $tarea->puntos_totales;
            $validated['puntos_obtenidos'] = round(($validated['calificacion'] / 100) * $tarea->puntos_totales);
        }

        Calificacion::create($validated);

        return redirect()->route('maestros.calificaciones.index')
            ->with('success', 'Calificación registrada exitosamente.');
    }

    public function edit(Calificacion $calificacion)
    {
        $this->authorize('update', $calificacion);
        
        $maestroId = Auth::id();
        $materias = Materia::whereHas('tareas', function($query) use ($maestroId) {
            $query->where('maestro_id', $maestroId);
        })->get();
        
        $tareas = Tarea::where('maestro_id', $maestroId)->where('estado', 'activa')->get();
        $alumnos = Alumno::all();
        
        return view('maestros.calificaciones.edit', compact('calificacion', 'materias', 'tareas', 'alumnos'));
    }

    public function update(Request $request, Calificacion $calificacion)
    {
        $this->authorize('update', $calificacion);
        
        $validated = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'tarea_id' => 'nullable|exists:tareas,id',
            'materia_id' => 'required|exists:materias,id',
            'calificacion' => 'required|numeric|min:0|max:100',
            'tipo_evaluacion' => 'required|in:tarea,examen_parcial,examen_final,proyecto,participacion,practica',
            'periodo_escolar' => 'required|string|max:20',
            'parcial' => 'nullable|integer|min:1|max:3',
            'puntos_obtenidos' => 'nullable|integer|min:0',
            'puntos_totales' => 'nullable|integer|min:1',
            'comentarios' => 'nullable|string',
            'fecha_evaluacion' => 'required|date',
        ]);

        // Si se especifica una tarea, obtener los puntos totales de la tarea
        if ($validated['tarea_id']) {
            $tarea = Tarea::find($validated['tarea_id']);
            $validated['puntos_totales'] = $tarea->puntos_totales;
            $validated['puntos_obtenidos'] = round(($validated['calificacion'] / 100) * $tarea->puntos_totales);
        }

        $calificacion->update($validated);

        return redirect()->route('maestros.calificaciones.index')
            ->with('success', 'Calificación actualizada exitosamente.');
    }

    public function destroy(Calificacion $calificacion)
    {
        $this->authorize('delete', $calificacion);
        $calificacion->delete();

        return redirect()->route('maestros.calificaciones.index')
            ->with('success', 'Calificación eliminada exitosamente.');
    }
    
    public function reportes()
    {
        $maestroId = Auth::id();
        
        // Obtener materias del maestro
        $materias = Materia::whereHas('calificaciones', function($query) use ($maestroId) {
            $query->where('maestro_id', $maestroId);
        })->get();
        
        return view('maestros.calificaciones.reportes.general', compact('materias'));
    }
    
    public function getTareasByMateria(Request $request)
    {
        $maestroId = Auth::id();
        $materiaId = $request->materia_id;
        
        $tareas = Tarea::where('maestro_id', $maestroId)
            ->where('materia_id', $materiaId)
            ->where('estado', 'activa')
            ->get();
        
        return response()->json($tareas);
    }
}
