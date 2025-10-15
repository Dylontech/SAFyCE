<?php

namespace App\Http\Controllers\Maestros;

use App\Http\Controllers\Controller;
use App\Models\Tarea;
use App\Models\Materia;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TareaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $maestroId = Auth::id();
        $tareas = Tarea::where('maestro_id', $maestroId)
            ->with(['materia', 'calificaciones'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('maestros.tareas.index', compact('tareas'));
    }

    public function create()
    {
        $materias = Materia::all();
        $grupos = \App\Models\Grupo::activos()->orderBy('semestre')->orderBy('letra')->get();
        $semestres = collect(range(1, 8))->map(function($sem) {
            return ['id' => $sem, 'nombre' => $sem . '° Semestre'];
        });
        
        return view('maestros.tareas.create', compact('materias', 'grupos', 'semestres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'materia_id' => 'required|exists:materias,id',
            'grupo' => 'required|string|exists:grupos,nombre_completo',
            'semestre' => 'required|in:1,2,3,4,5,6,7,8',
            'fecha_entrega' => 'required|date|after:now',
            'puntos_totales' => 'required|integer|min:1|max:100',
            'tipo' => 'required|in:tarea,proyecto,examen,practica,ensayo',
            'instrucciones' => 'nullable|string',
            'archivo_adjunto' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $validated['maestro_id'] = Auth::id();
        $validated['fecha_asignacion'] = now();
        $validated['estado'] = 'activa';

        if ($request->hasFile('archivo_adjunto')) {
            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')->store('tareas', 'public');
        }

        Tarea::create($validated);

        return redirect()->route('maestros.tareas.index')
            ->with('success', 'Tarea creada exitosamente.');
    }

    public function show(Tarea $tarea)
    {
        $this->authorize('view', $tarea);
        
        $calificaciones = $tarea->calificaciones()
            ->with('alumno')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('maestros.tareas.show', compact('tarea', 'calificaciones'));
    }

    public function edit(Tarea $tarea)
    {
        $this->authorize('update', $tarea);
        $materias = Materia::all();
        $grupos = \App\Models\Grupo::activos()->orderBy('semestre')->orderBy('letra')->get();
        $semestres = collect(range(1, 8))->map(function($sem) {
            return ['id' => $sem, 'nombre' => $sem . '° Semestre'];
        });
        return view('maestros.tareas.edit', compact('tarea', 'materias', 'grupos', 'semestres'));
    }

    /**
     * Ver entregas de una tarea específica
     */
    public function verEntregas(Tarea $tarea)
    {
        $this->authorize('view', $tarea);
        
        $entregas = Calificacion::with(['alumno'])
                                ->where('tarea_id', $tarea->id)
                                ->whereNotNull('archivo_entrega')
                                ->orderBy('fecha_entrega_alumno', 'desc')
                                ->paginate(15);
        
        return view('maestros.tareas.entregas', compact('tarea', 'entregas'));
    }

    /**
     * Descargar entrega de un alumno específico
     */
    public function descargarEntregaAlumno(Tarea $tarea, Calificacion $calificacion)
    {
        $this->authorize('view', $tarea);
        
        // Verificar que la calificación pertenece a esta tarea
        if ($calificacion->tarea_id !== $tarea->id) {
            abort(403, 'Entrega no válida para esta tarea.');
        }

        if (!$calificacion->archivo_entrega || !\Storage::disk('public')->exists($calificacion->archivo_entrega)) {
            abort(404, 'Archivo de entrega no encontrado.');
        }

        return \Storage::disk('public')->download($calificacion->archivo_entrega);
    }

    /**
     * Calificar una entrega específica
     */
    public function calificarEntrega(Request $request, Tarea $tarea, Calificacion $calificacion)
    {
        $this->authorize('update', $tarea);
        
        // Verificar que la calificación pertenece a esta tarea
        if ($calificacion->tarea_id !== $tarea->id) {
            abort(403, 'Entrega no válida para esta tarea.');
        }

        $validated = $request->validate([
            'calificacion' => 'required|numeric|min:0|max:' . $tarea->puntos_totales,
            'comentarios' => 'nullable|string|max:1000',
        ]);

        $calificacion->update([
            'calificacion' => $validated['calificacion'],
            'puntos_obtenidos' => $validated['calificacion'],
            'comentarios' => $validated['comentarios'],
            'estado_entrega' => 'calificada',
            'fecha_evaluacion' => now(),
        ]);

        return redirect()->back()->with('success', 'Tarea calificada exitosamente.');
    }

    public function update(Request $request, Tarea $tarea)
    {
        $this->authorize('update', $tarea);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'materia_id' => 'required|exists:materias,id',
            'grupo' => 'required|string|max:10',
            'semestre' => 'required|in:1,2,3,4,5,6,7,8',
            'fecha_entrega' => 'required|date',
            'puntos_totales' => 'required|integer|min:1|max:100',
            'tipo' => 'required|in:tarea,proyecto,examen,practica,ensayo',
            'estado' => 'required|in:activa,vencida,cancelada',
            'instrucciones' => 'nullable|string',
            'archivo_adjunto' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        if ($request->hasFile('archivo_adjunto')) {
            if ($tarea->archivo_adjunto) {
                Storage::disk('public')->delete($tarea->archivo_adjunto);
            }
            $validated['archivo_adjunto'] = $request->file('archivo_adjunto')->store('tareas', 'public');
        }

        $tarea->update($validated);

        return redirect()->route('maestros.tareas.index')
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $this->authorize('delete', $tarea);
        
        if ($tarea->archivo_adjunto) {
            Storage::disk('public')->delete($tarea->archivo_adjunto);
        }
        
        $tarea->delete();

        return redirect()->route('maestros.tareas.index')
            ->with('success', 'Tarea eliminada exitosamente.');
    }
    
    public function toggleEstado(Tarea $tarea)
    {
        $this->authorize('update', $tarea);
        
        $nuevoEstado = $tarea->estado === 'activa' ? 'cancelada' : 'activa';
        $tarea->update(['estado' => $nuevoEstado]);
        
        $mensaje = $nuevoEstado === 'activa' ? 'Tarea activada' : 'Tarea cancelada';
        
        return back()->with('success', $mensaje . ' exitosamente.');
    }
}
