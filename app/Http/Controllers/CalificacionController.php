<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Alumno;
use App\Models\Materia;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('ver calificaciones');

        $query = Calificacion::with(['alumno', 'materia', 'maestro', 'tarea']);

        // Filtros
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->filled('periodo_escolar')) {
            $query->where('periodo_escolar', $request->periodo_escolar);
        }

        if ($request->filled('tipo_evaluacion')) {
            $query->where('tipo_evaluacion', $request->tipo_evaluacion);
        }

        if ($request->filled('parcial')) {
            $query->where('parcial', $request->parcial);
        }

        // Si es maestro, solo mostrar sus calificaciones
        if (Auth::user()->esMaestro() && !Auth::user()->esAdmin()) {
            $query->where('maestro_id', Auth::id());
        }

        $calificaciones = $query->orderBy('fecha_evaluacion', 'desc')
                               ->paginate(15);

        // Datos para filtros
        $materias = Materia::orderBy('materia')->get();
        $periodos = Calificacion::distinct()->pluck('periodo_escolar');
        $tipos = ['tarea', 'examen_parcial', 'examen_final', 'proyecto', 'participacion', 'practica'];

        return view('calificaciones.index', compact('calificaciones', 'materias', 'periodos', 'tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('gestionar calificaciones');

        $materias = Materia::orderBy('materia')->get();
        $alumnos = collect(); // Solo admin puede ver todos los alumnos
        $tareas = collect();

        // Si es admin, puede ver todos los alumnos
        if (Auth::user()->esAdmin()) {
            $alumnos = Alumno::orderBy('Nombre')->get();
        }

        // Si viene de una tarea específica
        if ($request->filled('tarea_id')) {
            $tarea = Tarea::find($request->tarea_id);
            if ($tarea && (Auth::user()->esAdmin() || $tarea->maestro_id === Auth::id())) {
                $alumnos = Alumno::where('Grupo', $tarea->grupo)
                                ->where('semestre', $tarea->semestre)
                                ->get();
            }
        }

        // Si es maestro, filtrar materias que imparte
        if (Auth::user()->esMaestro() && !Auth::user()->esAdmin()) {
            $materias = Materia::whereHas('horarios', function ($query) {
                $query->where('maestro_id', Auth::id());
            })->distinct()->get();
        }

        return view('calificaciones.create', compact('materias', 'alumnos', 'tareas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('gestionar calificaciones');

        $request->validate(Calificacion::$rules);

        $data = $request->all();
        $data['maestro_id'] = Auth::id();

        $calificacion = Calificacion::create($data);

        return redirect()->route('calificaciones.index')
                        ->with('success', 'Calificación registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Calificacion $calificacion)
    {
        Gate::authorize('ver calificaciones');

        $calificacion->load(['alumno', 'materia', 'maestro', 'tarea']);

        return view('calificaciones.show', compact('calificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calificacion $calificacion)
    {
        Gate::authorize('editar calificaciones');

        // Solo el maestro que creó la calificación puede editarla
        if ($calificacion->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para editar esta calificación.');
        }

        $materias = Materia::orderBy('materia')->get();
        $alumnos = Alumno::orderBy('Nombre')->get();
        $tareas = Tarea::where('materia_id', $calificacion->materia_id)->get();

        return view('calificaciones.edit', compact('calificacion', 'materias', 'alumnos', 'tareas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calificacion $calificacion)
    {
        Gate::authorize('editar calificaciones');

        // Solo el maestro que creó la calificación puede editarla
        if ($calificacion->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para editar esta calificación.');
        }

        $request->validate(Calificacion::$rules);

        $calificacion->update($request->all());

        return redirect()->route('calificaciones.index')
                        ->with('success', 'Calificación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calificacion $calificacion)
    {
        Gate::authorize('editar calificaciones');

        // Solo el maestro que creó la calificación puede eliminarla
        if ($calificacion->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para eliminar esta calificación.');
        }

        $calificacion->delete();

        return redirect()->route('calificaciones.index')
                        ->with('success', 'Calificación eliminada exitosamente.');
    }

    /**
     * Calificar tarea específica
     */
    public function calificarTarea(Tarea $tarea)
    {
        Gate::authorize('calificar tareas');

        // Solo el maestro que creó la tarea puede calificarla
        if ($tarea->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para calificar esta tarea.');
        }

        // Obtener alumnos del grupo
        $alumnos = Alumno::where('Grupo', $tarea->grupo)
                        ->where('semestre', $tarea->semestre)
                        ->orderBy('Nombre')
                        ->get();

        // Obtener calificaciones existentes
        $calificacionesExistentes = Calificacion::where('tarea_id', $tarea->id)
                                               ->pluck('calificacion', 'alumno_id');

        return view('calificaciones.calificar-tarea', compact('tarea', 'alumnos', 'calificacionesExistentes'));
    }

    /**
     * Guardar calificaciones de tarea
     */
    public function guardarCalificacionesTarea(Request $request, Tarea $tarea)
    {
        Gate::authorize('calificar tareas');

        $request->validate([
            'calificaciones' => 'required|array',
            'calificaciones.*' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|array',
            'comentarios.*' => 'nullable|string|max:500'
        ]);

        foreach ($request->calificaciones as $alumno_id => $calificacion) {
            if ($calificacion !== null && $calificacion !== '') {
                Calificacion::updateOrCreate(
                    [
                        'alumno_id' => $alumno_id,
                        'tarea_id' => $tarea->id,
                    ],
                    [
                        'materia_id' => $tarea->materia_id,
                        'maestro_id' => Auth::id(),
                        'calificacion' => $calificacion,
                        'puntos_obtenidos' => ($calificacion * $tarea->puntos_totales) / 100,
                        'puntos_totales' => $tarea->puntos_totales,
                        'tipo_evaluacion' => 'tarea',
                        'periodo_escolar' => '2024-2025-1', // Esto debería ser dinámico
                        'comentarios' => $request->comentarios[$alumno_id] ?? null,
                        'fecha_evaluacion' => now()
                    ]
                );
            }
        }

        return redirect()->route('tareas.show', $tarea)
                        ->with('success', 'Calificaciones guardadas exitosamente.');
    }

    /**
     * Boleta de calificaciones por alumno
     */
    public function boleta(Alumno $alumno, $periodo = null)
    {
        Gate::authorize('ver calificaciones');
        
        // Solo admin puede ver boletas de cualquier alumno
        if (!Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para ver esta boleta.');
        }

        $periodo = $periodo ?? '2024-2025-1'; // Período actual por defecto

        $calificaciones = $alumno->calificaciones()
                                ->with(['materia', 'maestro'])
                                ->where('periodo_escolar', $periodo)
                                ->orderBy('materia_id')
                                ->get()
                                ->groupBy('materia_id');

        $promedio = $alumno->promedioGeneral($periodo);

        return view('calificaciones.boleta', compact('alumno', 'calificaciones', 'promedio', 'periodo'));
    }

    /**
     * Mis calificaciones (para alumnos)
     */
    public function misCalificaciones()
    {
        if (!Auth::user()->esAlumno()) {
            abort(403, 'No autorizado');
        }

        $alumno = Auth::user()->alumno;
        $periodo = '2024-2025-1'; // Período actual

        $calificaciones = $alumno->calificaciones()
                                ->with(['materia', 'maestro', 'tarea'])
                                ->where('periodo_escolar', $periodo)
                                ->orderBy('fecha_evaluacion', 'desc')
                                ->paginate(15);

        $promedio = $alumno->promedioGeneral($periodo);

        return view('calificaciones.mis-calificaciones', compact('calificaciones', 'promedio'));
    }

    /**
     * Obtener tareas por materia (AJAX)
     */
    public function obtenerTareasPorMateria(Request $request)
    {
        $materia_id = $request->materia_id;
        
        $tareas = Tarea::where('materia_id', $materia_id)
                      ->where('estado', 'activa')
                      ->orderBy('fecha_entrega', 'desc')
                      ->get();

        return response()->json($tareas);
    }
}
