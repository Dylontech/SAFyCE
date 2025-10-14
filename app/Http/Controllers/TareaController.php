<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Materia;
use App\Models\Alumno;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
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
        Gate::authorize('ver tareas');

        $query = Tarea::with(['materia', 'maestro']);

        // Filtros
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->filled('grupo')) {
            $query->where('grupo', $request->grupo);
        }

        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Si es maestro, solo mostrar sus tareas
        if (Auth::user()->esMaestro() && !Auth::user()->esAdmin()) {
            $query->where('maestro_id', Auth::id());
        }

        $tareas = $query->orderBy('fecha_entrega', 'desc')
                       ->paginate(15);

        // Datos para filtros
        $materias = Materia::orderBy('materia')->get();
        $grupos = Tarea::distinct()->pluck('grupo');
        $semestres = ['1', '2', '3', '4', '5', '6', '7', '8'];

        return view('tareas.index', compact('tareas', 'materias', 'grupos', 'semestres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('crear tareas');

        $materias = Materia::orderBy('materia')->get();
        
        // Si es maestro, filtrar materias que imparte
        if (Auth::user()->esMaestro() && !Auth::user()->esAdmin()) {
            $materias = Materia::whereHas('horarios', function ($query) {
                $query->where('maestro_id', Auth::id());
            })->distinct()->get();
        }

        return view('tareas.create', compact('materias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('crear tareas');

        $rules = Tarea::$rules;
        $rules['archivo_adjunto'] = 'nullable|file|mimes:pdf,doc,docx,txt,jpg,png|max:5120'; // 5MB

        $request->validate($rules);

        $data = $request->all();
        $data['maestro_id'] = Auth::id();

        // Manejar archivo adjunto
        if ($request->hasFile('archivo_adjunto')) {
            $archivo = $request->file('archivo_adjunto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->storeAs('tareas', $nombreArchivo, 'public');
            $data['archivo_adjunto'] = $rutaArchivo;
        }

        $tarea = Tarea::create($data);

        return redirect()->route('tareas.index')
                        ->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarea $tarea)
    {
        Gate::authorize('ver tareas');

        $tarea->load(['materia', 'maestro']);

        // Obtener calificaciones de la tarea
        $calificaciones = $tarea->calificaciones()
                               ->with('alumno')
                               ->orderBy('calificacion', 'desc')
                               ->get();

        // Obtener alumnos del grupo que no han sido calificados
        $alumnosGrupo = collect();
        
        // Solo mostrar alumnos si es admin o el maestro que creó la tarea
        if (Auth::user()->esAdmin() || $tarea->maestro_id === Auth::id()) {
            $alumnosGrupo = Alumno::where('Grupo', $tarea->grupo)
                                 ->where('semestre', $tarea->semestre)
                                 ->whereNotIn('id', $calificaciones->pluck('alumno_id'))
                                 ->get();
        }

        return view('tareas.show', compact('tarea', 'calificaciones', 'alumnosGrupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarea $tarea)
    {
        Gate::authorize('editar tareas');

        // Solo el maestro que creó la tarea puede editarla
        if ($tarea->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para editar esta tarea.');
        }

        $materias = Materia::orderBy('materia')->get();
        
        // Si es maestro, filtrar materias que imparte
        if (Auth::user()->esMaestro() && !Auth::user()->esAdmin()) {
            $materias = Materia::whereHas('horarios', function ($query) {
                $query->where('maestro_id', Auth::id());
            })->distinct()->get();
        }

        return view('tareas.edit', compact('tarea', 'materias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarea $tarea)
    {
        Gate::authorize('editar tareas');

        // Solo el maestro que creó la tarea puede editarla
        if ($tarea->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para editar esta tarea.');
        }

        $rules = Tarea::$rules;
        $rules['archivo_adjunto'] = 'nullable|file|mimes:pdf,doc,docx,txt,jpg,png|max:5120';

        $request->validate($rules);

        $data = $request->all();

        // Manejar archivo adjunto
        if ($request->hasFile('archivo_adjunto')) {
            // Eliminar archivo anterior si existe
            if ($tarea->archivo_adjunto) {
                Storage::disk('public')->delete($tarea->archivo_adjunto);
            }

            $archivo = $request->file('archivo_adjunto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->storeAs('tareas', $nombreArchivo, 'public');
            $data['archivo_adjunto'] = $rutaArchivo;
        }

        $tarea->update($data);

        return redirect()->route('tareas.index')
                        ->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarea $tarea)
    {
        Gate::authorize('eliminar tareas');

        // Solo el maestro que creó la tarea puede eliminarla
        if ($tarea->maestro_id !== Auth::id() && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado para eliminar esta tarea.');
        }

        // Eliminar archivo adjunto si existe
        if ($tarea->archivo_adjunto) {
            Storage::disk('public')->delete($tarea->archivo_adjunto);
        }

        $tarea->delete();

        return redirect()->route('tareas.index')
                        ->with('success', 'Tarea eliminada exitosamente.');
    }

    /**
     * Descargar archivo adjunto de la tarea
     */
    public function descargarArchivo(Tarea $tarea)
    {
        Gate::authorize('ver tareas');

        if (!$tarea->archivo_adjunto || !Storage::disk('public')->exists($tarea->archivo_adjunto)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('public')->download($tarea->archivo_adjunto);
    }

    /**
     * Marcar tareas vencidas como vencidas automáticamente
     */
    public function marcarVencidas()
    {
        $tareasVencidas = Tarea::where('fecha_entrega', '<', now())
                              ->where('estado', 'activa')
                              ->update(['estado' => 'vencida']);

        return response()->json([
            'message' => "Se marcaron {$tareasVencidas} tareas como vencidas."
        ]);
    }

    /**
     * Mis tareas (para alumnos)
     */
    public function misTareas()
    {
        if (!Auth::user()->esAlumno()) {
            abort(403, 'No autorizado');
        }

        $alumno = Auth::user()->alumno;
        
        $tareas = Tarea::with(['materia', 'maestro'])
                      ->where('grupo', $alumno->Grupo)
                      ->where('semestre', $alumno->semestre)
                      ->orderBy('fecha_entrega', 'asc')
                      ->paginate(10);

        // Obtener calificaciones del alumno para estas tareas
        $calificaciones = Calificacion::where('alumno_id', $alumno->id)
                                    ->whereIn('tarea_id', $tareas->pluck('id'))
                                    ->pluck('calificacion', 'tarea_id');

        return view('tareas.mis-tareas', compact('tareas', 'calificaciones'));
    }
}
