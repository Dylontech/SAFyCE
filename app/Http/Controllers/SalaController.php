<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalaController extends Controller
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
        Gate::authorize('ver salas');

        $query = Sala::query();

        // Aplicar filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%")
                  ->orWhere('ubicacion', 'like', "%{$buscar}%");
            });
        }

        $salas = $query->orderBy('nombre')->paginate(15);

        return view('salas.index', compact('salas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('gestionar salas');

        return view('salas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('gestionar salas');

        $request->validate(Sala::$rules);

        $sala = Sala::create($request->all());

        return redirect()->route('salas.index')
                        ->with('success', 'Sala creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sala $sala)
    {
        Gate::authorize('ver salas');

        // Obtener horarios de la sala para mostrar disponibilidad
        $horarios = $sala->horarios()
                         ->with(['materia', 'maestro'])
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

        return view('salas.show', compact('sala', 'horarios', 'reuniones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sala $sala)
    {
        Gate::authorize('gestionar salas');

        return view('salas.edit', compact('sala'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sala $sala)
    {
        Gate::authorize('gestionar salas');

        $rules = Sala::$rules;
        $rules['nombre'] = 'required|string|max:255|unique:salas,nombre,' . $sala->id;
        $rules['codigo'] = 'required|string|max:50|unique:salas,codigo,' . $sala->id;

        $request->validate($rules);

        $sala->update($request->all());

        return redirect()->route('salas.index')
                        ->with('success', 'Sala actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sala $sala)
    {
        Gate::authorize('gestionar salas');

        // Verificar si la sala tiene horarios activos
        if ($sala->horarios()->activos()->exists()) {
            return redirect()->route('salas.index')
                           ->with('error', 'No se puede eliminar la sala porque tiene horarios activos asignados.');
        }

        $sala->delete();

        return redirect()->route('salas.index')
                        ->with('success', 'Sala eliminada exitosamente.');
    }

    /**
     * Verificar disponibilidad de una sala
     */
    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'sala_id' => 'required|exists:salas,id',
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'excluir_horario_id' => 'nullable|exists:horarios,id'
        ]);

        $sala = Sala::find($request->sala_id);
        $disponible = $sala->estaDisponible(
            $request->dia_semana,
            $request->hora_inicio,
            $request->hora_fin,
            $request->excluir_horario_id
        );

        return response()->json([
            'disponible' => $disponible,
            'mensaje' => $disponible ? 'La sala está disponible.' : 'La sala no está disponible en ese horario.'
        ]);
    }

    /**
     * Crear una nueva reunión
     */
    public function crearReunion(Request $request)
    {
        Gate::authorize('crear reuniones');

        // Validación personalizada para el formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracion_minutos' => 'required|integer|min:15|max:480',
            'plataforma' => 'required|in:meet,zoom,teams,webex',
            'enlace' => 'required|url',
            'sala_id' => 'required|exists:salas,id',
            'descripcion' => 'nullable|string|max:1000'
        ]);

        try {
            $reunion = Reunion::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'duracion' => $request->duracion_minutos,
                'plataforma' => $request->plataforma,
                'enlace_reunion' => $request->enlace,
                'tipo' => 'clase', // Valor por defecto
                'estado' => 'activa', // Valor por defecto
                'sala_id' => $request->sala_id,
                'user_id' => Auth::id(),
                'codigo_reunion' => $this->generarCodigoReunion($request->plataforma),
                'max_participantes' => 100
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reunión creada exitosamente',
                'reunion' => [
                    'id' => $reunion->id,
                    'titulo' => $reunion->titulo,
                    'fecha' => $reunion->fecha_formateada,
                    'hora' => $reunion->hora_formateada,
                    'enlace' => $reunion->enlace_reunion,
                    'plataforma' => $reunion->plataforma
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al crear reunión: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la reunión: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener reuniones de una sala
     */
    public function reunionesSala(Sala $sala)
    {
        Gate::authorize('ver salas');

        $reuniones = $sala->reuniones()
                         ->with('creador')
                         ->proximasActivas()
                         ->get();

        return response()->json([
            'reuniones' => $reuniones->map(function($reunion) {
                return [
                    'id' => $reunion->id,
                    'titulo' => $reunion->titulo,
                    'fecha' => $reunion->fecha_formateada,
                    'hora' => $reunion->hora_formateada,
                    'duracion' => $reunion->duracion_formateada,
                    'plataforma' => $reunion->plataforma,
                    'tipo' => $reunion->tipo,
                    'estado' => $reunion->estado,
                    'creador' => $reunion->creador->name,
                    'puede_unirse' => $reunion->puedeUnirse(),
                    'enlace' => $reunion->enlace_reunion
                ];
            })
        ]);
    }

    /**
     * Obtener reunión específica para unirse
     */
    public function obtenerReunion(Reunion $reunion)
    {
        if (!$reunion->puedeUnirse()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta reunión no está disponible en este momento'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'reunion' => [
                'id' => $reunion->id,
                'titulo' => $reunion->titulo,
                'descripcion' => $reunion->descripcion,
                'enlace' => $reunion->enlace_reunion,
                'plataforma' => $reunion->plataforma
            ]
        ]);
    }

    /**
     * Cancelar una reunión
     */
    public function cancelarReunion(Reunion $reunion)
    {
        Gate::authorize('crear reuniones');

        // Solo el creador o un administrador puede cancelar
        if ($reunion->user_id !== Auth::id() && !Auth::user()->hasRole('administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para cancelar esta reunión'
            ], 403);
        }

        $reunion->update(['estado' => 'cancelada']);

        return response()->json([
            'success' => true,
            'message' => 'Reunión cancelada exitosamente'
        ]);
    }

    /**
     * Eliminar una reunión
     */
    public function eliminarReunion(Reunion $reunion)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $user = Auth::user();

        // Verificar permisos: creador, administrador, control escolar, o permiso específico
        $esCreador = $reunion->user_id === $user->id;
        $esAdministrador = $user->hasRole('administrador');
        $esControlEscolar = $user->hasRole('control_escolar');
        $tienePermisoEliminar = $user->can('eliminar reuniones');

        $puedeEliminar = $esCreador || $esAdministrador || $esControlEscolar || $tienePermisoEliminar;

        if (!$puedeEliminar) {
            \Log::warning('Acceso denegado para eliminar reunión', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_roles' => $user->getRoleNames(),
                'reunion_id' => $reunion->id,
                'es_creador' => $esCreador,
                'es_administrador' => $esAdministrador,
                'es_control_escolar' => $esControlEscolar,
                'tiene_permiso_eliminar' => $tienePermisoEliminar
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar esta reunión.',
                'debug_info' => [
                    'user_roles' => $user->getRoleNames(),
                    'es_creador' => $esCreador,
                    'es_administrador' => $esAdministrador,
                    'es_control_escolar' => $esControlEscolar,
                    'tiene_permiso_eliminar' => $tienePermisoEliminar
                ]
            ], 403);
        }

        $tituloReunion = $reunion->titulo;
        $reunion->delete();

        return response()->json([
            'success' => true,
            'message' => "Reunión '{$tituloReunion}' eliminada exitosamente"
        ]);
    }

    /**
     * Generar código único para la reunión según la plataforma
     */
    private function generarCodigoReunion($plataforma)
    {
        switch ($plataforma) {
            case 'google_meet':
            case 'meet':
                // Para Google Meet: formato abc-defg-hij
                return strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3) . '-' . 
                                 substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 4) . '-' . 
                                 substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3));
                
            case 'zoom':
                // Para Zoom: número de 10-11 dígitos
                return rand(1000000000, 99999999999);
                
            case 'teams':
                // Para Teams: ID único
                return uniqid('teams_', true);
                
            case 'webex':
                // Para Webex: número de meeting
                return rand(100000000, 999999999);
                
            default:
                return uniqid('reunion_', true);
        }
    }
}
