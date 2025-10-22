<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Sala;
use App\Models\Materia;
use App\Models\Tarea;
use App\Models\Calificacion;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EstudianteController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:alumno');
    }

    /**
     * Display a listing of horarios for students.
     */
    public function horarios(Request $request)
    {
        $query = Horario::with(['materia', 'maestro', 'sala'])->activos();

        // Filtros
        if ($request->filled('dia_semana')) {
            $query->where('dia_semana', $request->dia_semana);
        }

        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->filled('maestro')) {
            $query->whereHas('maestro', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->maestro . '%');
            });
        }

        $horarios = $query->orderBy('dia_semana')
                         ->orderBy('hora_inicio')
                         ->paginate(15);

        // Para los filtros
        $materias = Materia::orderBy('materia')->get();
        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado'
        ];

        return view('estudiantes.horarios.index', compact('horarios', 'materias', 'dias'));
    }

    /**
     * Display the specified horario for students.
     */
    public function showHorario(Horario $horario)
    {
        $horario->load(['materia', 'maestro', 'sala']);
        
        return view('estudiantes.horarios.show', compact('horario'));
    }

    /**
     * Display a listing of salas for students.
     */
    public function salas(Request $request)
    {
        $query = Sala::query();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
            });
        }

        $salas = $query->orderBy('nombre')->paginate(12);

        // Para los filtros
        $tipos = Sala::distinct()->pluck('tipo')->filter()->sort();
        $estados = ['disponible', 'ocupada', 'mantenimiento'];

        return view('estudiantes.salas.index', compact('salas', 'tipos', 'estados'));
    }

    /**
     * Display the specified sala for students.
     */
    public function showSala(Sala $sala)
    {
        // Horarios actuales de esta sala
        $horariosActuales = Horario::with(['materia', 'maestro'])
                                  ->where('sala_id', $sala->id)
                                  ->activos()
                                  ->orderBy('dia_semana')
                                  ->orderBy('hora_inicio')
                                  ->get();

        // Obtener reuniones de la sala (próximas y de hoy)
        $reuniones = $sala->reuniones()
                          ->with('creador')
                          ->proximasActivas()
                          ->limit(5)
                          ->get();

        return view('estudiantes.salas.show', compact('sala', 'horariosActuales', 'reuniones'));
    }

    /**
     * Show weekly schedule view for students.
     */
    public function horarioSemanal()
    {
        $horarios = Horario::with(['materia', 'maestro', 'sala'])
                          ->activos()
                          ->orderBy('dia_semana')
                          ->orderBy('hora_inicio')
                          ->get();

        // Organizar horarios por día
        $horariosPorDia = $horarios->groupBy('dia_semana');

        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado'
        ];

        return view('estudiantes.horarios.semanal', compact('horariosPorDia', 'dias'));
    }

    /**
     * Display a listing of tareas for students.
     */
    public function tareas(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        
        $query = Tarea::with(['materia', 'maestro'])
                     ->where('grupo', $alumno->Grupo)
                     ->where('semestre', $alumno->semestre);

        // Filtros
        if ($request->filled('estado')) {
            if ($request->estado === 'pendientes') {
                $query->where('estado', 'activa')
                     ->where('fecha_entrega', '>=', now());
            } elseif ($request->estado === 'vencidas') {
                $query->where('fecha_entrega', '<', now())
                     ->where('estado', 'activa');
            } else {
                $query->where('estado', $request->estado);
            }
        }

        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        $tareas = $query->orderBy('fecha_entrega', 'asc')
                       ->paginate(10);

        // Obtener calificaciones/entregas del alumno para estas tareas
        $entregasIds = $tareas->pluck('id');
        $entregas = Calificacion::where('alumno_id', $alumno->id)
                                ->whereIn('tarea_id', $entregasIds)
                                ->pluck('estado_entrega', 'tarea_id');

        // Para los filtros
        $materias = Materia::where('especialidad', $alumno->especialidad)
                          ->where('semestre', $alumno->semestre)
                          ->orderBy('materia')
                          ->get();

        $tipos = [
            'tarea' => 'Tarea',
            'proyecto' => 'Proyecto',
            'examen' => 'Examen',
            'practica' => 'Práctica',
            'ensayo' => 'Ensayo'
        ];

        return view('estudiantes.tareas.index', compact('tareas', 'materias', 'tipos', 'alumno', 'entregas'));
    }

    /**
     * Display the specified tarea for students.
     */
    public function showTarea(Tarea $tarea)
    {
        $alumno = Auth::guard('alumno')->user();
        
        // Verificar que la tarea corresponde al grupo y semestre del alumno
        if ($tarea->grupo !== $alumno->Grupo || $tarea->semestre !== $alumno->semestre) {
            abort(403, 'No tienes acceso a esta tarea.');
        }

        $tarea->load(['materia', 'maestro']);
        
        // Verificar si el alumno ya tiene calificación para esta tarea
        $calificacion = $tarea->calificaciones()
                             ->where('alumno_id', $alumno->id)
                             ->first();

        return view('estudiantes.tareas.show', compact('tarea', 'calificacion', 'alumno'));
    }

    /**
     * Descargar archivo adjunto de tarea para estudiantes
     */
    public function descargarArchivoTarea(Tarea $tarea)
    {
        $alumno = Auth::guard('alumno')->user();
        
        // Verificar que la tarea corresponde al grupo y semestre del alumno
        if ($tarea->grupo !== $alumno->Grupo || $tarea->semestre != $alumno->semestre) {
            abort(403, 'No autorizado para descargar este archivo.');
        }

        if (!$tarea->archivo_adjunto || !Storage::disk('public')->exists($tarea->archivo_adjunto)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('public')->download($tarea->archivo_adjunto);
    }

    /**
     * Subir entrega de tarea del estudiante
     */
    public function subirEntregaTarea(Request $request, Tarea $tarea)
    {
        $alumno = Auth::guard('alumno')->user();
        
        // Verificar que la tarea corresponde al grupo y semestre del alumno
        if ($tarea->grupo !== $alumno->Grupo || $tarea->semestre != $alumno->semestre) {
            abort(403, 'No autorizado para entregar esta tarea.');
        }

        // Verificar que la tarea no esté vencida (opcional, puede permitir entregas tardías)
        $esTarde = now()->isAfter($tarea->fecha_entrega);

        $request->validate([
            'archivo_entrega' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Verificar si ya existe una entrega para esta tarea y alumno
        $calificacion = Calificacion::where('tarea_id', $tarea->id)
                                   ->where('alumno_id', $alumno->id)
                                   ->first();

        if (!$calificacion) {
            // Crear nueva calificación/entrega
            $calificacion = new Calificacion();
            $calificacion->alumno_id = $alumno->id;
            $calificacion->tarea_id = $tarea->id;
            $calificacion->materia_id = $tarea->materia_id;
            $calificacion->maestro_id = $tarea->maestro_id;
            $calificacion->tipo_evaluacion = $tarea->tipo;
            $calificacion->puntos_totales = $tarea->puntos_totales;
            $calificacion->periodo_escolar = now()->year . '-' . (now()->year + 1) . '-' . (now()->month <= 6 ? '2' : '1');
        } else {
            // Si ya tenía archivo anterior, eliminarlo
            if ($calificacion->archivo_entrega && Storage::disk('public')->exists($calificacion->archivo_entrega)) {
                Storage::disk('public')->delete($calificacion->archivo_entrega);
            }
        }

        // Subir el nuevo archivo
        $path = $request->file('archivo_entrega')->store('entregas/' . $tarea->id, 'public');
        
        $calificacion->archivo_entrega = $path;
        $calificacion->fecha_entrega_alumno = now();
        $calificacion->estado_entrega = $esTarde ? 'tarde' : 'entregada';
        
        $calificacion->save();

        $mensaje = $esTarde 
            ? 'Tarea entregada exitosamente, pero fue entregada después de la fecha límite.'
            : 'Tarea entregada exitosamente.';

        return redirect()->back()->with('success', $mensaje);
    }

    /**
     * Descargar entrega del estudiante (para que pueda ver su propio archivo)
     */
    public function descargarMiEntrega(Tarea $tarea)
    {
        $alumno = Auth::guard('alumno')->user();
        
        $calificacion = Calificacion::where('tarea_id', $tarea->id)
                                   ->where('alumno_id', $alumno->id)
                                   ->first();

        if (!$calificacion || !$calificacion->archivo_entrega) {
            abort(404, 'No hay entrega para esta tarea.');
        }

        if (!Storage::disk('public')->exists($calificacion->archivo_entrega)) {
            abort(404, 'Archivo de entrega no encontrado.');
        }

        return Storage::disk('public')->download($calificacion->archivo_entrega);
    }

    /**
     * Display a listing of calificaciones for students.
     */
    public function calificaciones(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        
        $query = Calificacion::with(['materia', 'maestro', 'tarea'])
                            ->where('alumno_id', $alumno->id);

        // Filtros
        if ($request->filled('periodo_escolar')) {
            $query->where('periodo_escolar', $request->periodo_escolar);
        }

        if ($request->filled('parcial')) {
            $query->where('parcial', $request->parcial);
        }

        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->filled('tipo_evaluacion')) {
            $query->where('tipo_evaluacion', $request->tipo_evaluacion);
        }

        $calificaciones = $query->orderBy('created_at', 'desc')
                               ->paginate(15);

        // Para los filtros
        $materias = Materia::where('especialidad', $alumno->especialidad)
                          ->where('semestre', $alumno->semestre)
                          ->orderBy('materia')
                          ->get();

        $periodos = Calificacion::where('alumno_id', $alumno->id)
                               ->distinct()
                               ->pluck('periodo_escolar')
                               ->filter()
                               ->sort();

        $tipos = [
            'tarea' => 'Tarea',
            'examen_parcial' => 'Examen Parcial',
            'examen_final' => 'Examen Final',
            'proyecto' => 'Proyecto',
            'participacion' => 'Participación',
            'practica' => 'Práctica'
        ];

        // Estadísticas generales
        $promedioGeneral = $calificaciones->avg('calificacion') ?? 0;
        $totalCalificaciones = $calificaciones->total();
        $aprobadas = $calificaciones->where('calificacion', '>=', 70)->count();
        $reprobadas = $calificaciones->where('calificacion', '<', 70)->count();

        return view('estudiantes.calificaciones.index', compact(
            'calificaciones', 'materias', 'periodos', 'tipos', 'alumno',
            'promedioGeneral', 'totalCalificaciones', 'aprobadas', 'reprobadas'
        ));
    }

    /**
     * Display reports of calificaciones for students.
     */
    public function reportesCalificaciones(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        
        // Calificaciones por materia
        $calificacionesPorMateria = Calificacion::with(['materia'])
                                               ->where('alumno_id', $alumno->id)
                                               ->get()
                                               ->groupBy('materia.materia')
                                               ->map(function ($calificaciones) {
                                                   return [
                                                       'promedio' => $calificaciones->avg('calificacion'),
                                                       'total' => $calificaciones->count(),
                                                       'aprobadas' => $calificaciones->where('calificacion', '>=', 70)->count(),
                                                       'reprobadas' => $calificaciones->where('calificacion', '<', 70)->count(),
                                                       'calificaciones' => $calificaciones
                                                   ];
                                               });

        // Calificaciones por período
        $calificacionesPorPeriodo = Calificacion::where('alumno_id', $alumno->id)
                                                ->get()
                                                ->groupBy('periodo_escolar')
                                                ->map(function ($calificaciones) {
                                                    return [
                                                        'promedio' => $calificaciones->avg('calificacion'),
                                                        'total' => $calificaciones->count(),
                                                        'aprobadas' => $calificaciones->where('calificacion', '>=', 70)->count(),
                                                        'reprobadas' => $calificaciones->where('calificacion', '<', 70)->count()
                                                    ];
                                                });

        // Calificaciones por tipo de evaluación
        $calificacionesPorTipo = Calificacion::where('alumno_id', $alumno->id)
                                            ->get()
                                            ->groupBy('tipo_evaluacion')
                                            ->map(function ($calificaciones) {
                                                return [
                                                    'promedio' => $calificaciones->avg('calificacion'),
                                                    'total' => $calificaciones->count(),
                                                    'aprobadas' => $calificaciones->where('calificacion', '>=', 70)->count(),
                                                    'reprobadas' => $calificaciones->where('calificacion', '<', 70)->count()
                                                ];
                                            });

        // Estadísticas generales
        $promedioGeneral = Calificacion::where('alumno_id', $alumno->id)->avg('calificacion') ?? 0;
        $totalCalificaciones = Calificacion::where('alumno_id', $alumno->id)->count();

        return view('estudiantes.calificaciones.reportes', compact(
            'calificacionesPorMateria', 'calificacionesPorPeriodo', 'calificacionesPorTipo',
            'promedioGeneral', 'totalCalificaciones', 'alumno'
        ));
    }

    /**
     * Display boleta de calificaciones for students.
     */
    public function boleta(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        
        $periodo = $request->get('periodo', date('Y') . '-' . (date('m') > 6 ? '2' : '1'));
        
        // Calificaciones del período seleccionado
        $calificaciones = Calificacion::with(['materia', 'maestro'])
                                     ->where('alumno_id', $alumno->id)
                                     ->where('periodo_escolar', $periodo)
                                     ->orderBy('materia_id')
                                     ->get()
                                     ->groupBy('materia.materia');

        // Promedios por materia
        $promediosPorMateria = $calificaciones->map(function ($calificacionesMateria) {
            return $calificacionesMateria->avg('calificacion');
        });

        $promedioGeneral = $promediosPorMateria->avg() ?? 0;

        // Períodos disponibles
        $periodos = Calificacion::where('alumno_id', $alumno->id)
                               ->distinct()
                               ->pluck('periodo_escolar')
                               ->filter()
                               ->sort();

        return view('estudiantes.calificaciones.boleta', compact(
            'calificaciones', 'promediosPorMateria', 'promedioGeneral',
            'periodo', 'periodos', 'alumno'
        ));
    }

    /**
     * Display a listing of reuniones for students.
     */
    public function reuniones(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        
        $query = Reunion::with(['creador', 'sala'])
                       ->activas();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('plataforma')) {
            $query->where('plataforma', $request->plataforma);
        }

        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        $reuniones = $query->orderBy('fecha', 'asc')
                          ->orderBy('hora', 'asc')
                          ->paginate(10);

        // Para los filtros
        $tipos = [
            'clase' => 'Clase',
            'tutorial' => 'Tutorial',
            'reunion' => 'Reunión',
            'examen' => 'Examen',
            'otro' => 'Otro'
        ];

        $plataformas = [
            'meet' => 'Google Meet',
            'zoom' => 'Zoom',
            'teams' => 'Microsoft Teams',
            'webex' => 'Cisco Webex'
        ];

        return view('estudiantes.reuniones.index', compact('reuniones', 'tipos', 'plataformas', 'alumno'));
    }

    /**
     * Display active reuniones for students.
     */
    public function reunionesActivas(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        
        // Reuniones que están en curso ahora mismo
        $reunionesEnCurso = Reunion::with(['creador', 'sala'])
                                  ->activas()
                                  ->get()
                                  ->filter(function ($reunion) {
                                      return $reunion->estaEnCurso();
                                  });

        // Reuniones que empiezan en los próximos 15 minutos
        $reunionesProximas = Reunion::with(['creador', 'sala'])
                                   ->activas()
                                   ->get()
                                   ->filter(function ($reunion) {
                                       $fechaHoraReunion = Carbon::parse($reunion->fecha_hora);
                                       $ahora = now();
                                       return $ahora->between(
                                           $fechaHoraReunion->copy()->subMinutes(15),
                                           $fechaHoraReunion
                                       );
                                   });

        // Reuniones de hoy (que aún no han empezado)
        $reunionesHoy = Reunion::with(['creador', 'sala'])
                              ->activas()
                              ->hoy()
                              ->get()
                              ->filter(function ($reunion) {
                                  $fechaHoraReunion = Carbon::parse($reunion->fecha_hora);
                                  return now()->lt($fechaHoraReunion->copy()->subMinutes(15));
                              })
                              ->sortBy('hora');

        // Estadísticas
        $totalActivas = $reunionesEnCurso->count() + $reunionesProximas->count();
        $totalHoy = $reunionesHoy->count() + $totalActivas;

        return view('estudiantes.reuniones.activas', compact(
            'reunionesEnCurso', 'reunionesProximas', 'reunionesHoy', 
            'totalActivas', 'totalHoy', 'alumno'
        ));
    }

    /**
     * Join a reunion.
     */
    public function unirseReunion(Reunion $reunion)
    {
        $alumno = Auth::guard('alumno')->user();
        
        // Verificar si la reunión está activa y se puede unir
        if (!$reunion->puedeUnirse()) {
            return redirect()->back()->with('error', 'No es posible unirse a esta reunión en este momento.');
        }

        // Verificar límite de participantes
        if ($reunion->max_participantes && $reunion->participantes_actuales >= $reunion->max_participantes) {
            return redirect()->back()->with('error', 'La reunión ha alcanzado el límite máximo de participantes.');
        }

        // Incrementar contador de participantes
        $reunion->increment('participantes_actuales');

        // Generar el enlace de la reunión
        $enlaceReunion = $reunion->generarEnlaceReunion();

        // Log de acceso (opcional)
        \Log::info("Alumno {$alumno->numero_control} se unió a la reunión {$reunion->id} - {$reunion->titulo}");

        // Redirigir al enlace de la reunión
        return redirect()->away($enlaceReunion);
    }
}
