<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\UsuarioBloqueado;
use App\Models\HistorialModeracion;
use App\Models\Publicacion;
use App\Models\Comentario;
use App\Models\Alumno;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModeracionController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados con permisos de moderación
        $this->middleware('auth');
    }

    /**
     * Dashboard principal de moderación
     */
    public function dashboard()
    {
        $estadisticas = [
            'reportes_pendientes' => Reporte::pendientes()->count(),
            'reportes_en_revision' => Reporte::enRevision()->count(),
            'usuarios_bloqueados' => UsuarioBloqueado::activos()->count(),
            'acciones_hoy' => HistorialModeracion::whereDate('created_at', today())->count()
        ];

        $reportesRecientes = Reporte::with(['reportable', 'reportadoPor'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $actividadReciente = HistorialModeracion::actividadReciente(15);

        $reportesPorTipo = Reporte::selectRaw('tipo_reporte, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('tipo_reporte')
            ->pluck('total', 'tipo_reporte');

        return view('moderacion.dashboard', compact(
            'estadisticas',
            'reportesRecientes', 
            'actividadReciente',
            'reportesPorTipo'
        ));
    }

    /**
     * Lista de reportes
     */
    public function reportes(Request $request)
    {
        $query = Reporte::with(['reportable', 'reportadoPor', 'asignadoA']);

        // Filtros
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->tipo_reporte) {
            $query->where('tipo_reporte', $request->tipo_reporte);
        }

        if ($request->asignado_a === 'yo') {
            $query->where('asignado_a', Auth::id());
        }

        $reportes = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('moderacion.reportes.index', compact('reportes'));
    }

    /**
     * Ver detalle de un reporte
     */
    public function verReporte($id)
    {
        $reporte = Reporte::with(['reportable', 'reportadoPor', 'asignadoA'])->findOrFail($id);
        
        return view('moderacion.reportes.show', compact('reporte'));
    }

    /**
     * Asignar reporte a moderador
     */
    public function asignarReporte(Request $request, $id)
    {
        $reporte = Reporte::findOrFail($id);
        $reporte->asignar(Auth::id());

        return redirect()->back()->with('success', 'Reporte asignado correctamente');
    }

    /**
     * Resolver reporte
     */
    public function resolverReporte(Request $request, $id)
    {
        $request->validate([
            'accion_tomada' => 'required|in:sin_accion,advertencia,eliminacion_contenido,bloqueo_temporal,bloqueo_permanente',
            'respuesta_moderador' => 'required|string|max:500',
            'dias_bloqueo' => 'required_if:accion_tomada,bloqueo_temporal|integer|min:1|max:365'
        ]);

        $reporte = Reporte::with('reportable')->findOrFail($id);
        
        DB::transaction(function() use ($request, $reporte) {
            // Resolver el reporte
            $reporte->resolver(Auth::id(), $request->accion_tomada, $request->respuesta_moderador);

            // Ejecutar la acción correspondiente
            $this->ejecutarAccion($request->accion_tomada, $reporte, $request);
        });

        return redirect()->route('moderacion.reportes.index')->with('success', 'Reporte resuelto correctamente');
    }

    /**
     * Eliminar publicación
     */
    public function eliminarPublicacion($id, Request $request)
    {
        $publicacion = Publicacion::findOrFail($id);
        
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        DB::transaction(function() use ($publicacion, $request) {
            // Guardar respaldo en el historial
            HistorialModeracion::registrarAccion(
                Auth::id(),
                'eliminar_publicacion',
                $request->motivo,
                null,
                $publicacion->perfil->alumno_id,
                $publicacion,
                [
                    'contenido_original' => $publicacion->contenido,
                    'archivos' => $publicacion->archivos,
                    'fecha_publicacion' => $publicacion->created_at
                ]
            );

            // Eliminar la publicación
            $publicacion->delete();
        });

        return redirect()->back()->with('success', 'Publicación eliminada correctamente');
    }

    /**
     * Eliminar comentario
     */
    public function eliminarComentario($id, Request $request)
    {
        $comentario = Comentario::findOrFail($id);
        
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        DB::transaction(function() use ($comentario, $request) {
            // Guardar respaldo en el historial
            HistorialModeracion::registrarAccion(
                Auth::id(),
                'eliminar_comentario',
                $request->motivo,
                null,
                $comentario->perfil->alumno_id,
                $comentario,
                [
                    'contenido_original' => $comentario->contenido,
                    'fecha_comentario' => $comentario->created_at
                ]
            );

            // Eliminar el comentario
            $comentario->delete();
        });

        return redirect()->back()->with('success', 'Comentario eliminado correctamente');
    }

    /**
     * Bloquear usuario
     */
    public function bloquearUsuario(Request $request, $alumnoId)
    {
        $request->validate([
            'tipo_bloqueo' => 'required|in:temporal,permanente',
            'motivo' => 'required|string|max:500',
            'detalles' => 'nullable|string|max:1000',
            'dias' => 'required_if:tipo_bloqueo,temporal|integer|min:1|max:365'
        ]);

        $alumno = Alumno::findOrFail($alumnoId);

        // Verificar si ya está bloqueado
        if (UsuarioBloqueado::estaAlumnoBloqueado($alumnoId)) {
            return redirect()->back()->with('error', 'El usuario ya está bloqueado');
        }

        $fechaFin = null;
        if ($request->tipo_bloqueo === 'temporal') {
            $fechaFin = now()->addDays($request->dias);
        }

        $bloqueo = UsuarioBloqueado::create([
            'alumno_id' => $alumnoId,
            'bloqueado_por' => Auth::id(),
            'tipo_bloqueo' => $request->tipo_bloqueo,
            'motivo' => $request->motivo,
            'detalles' => $request->detalles,
            'fecha_inicio' => now(),
            'fecha_fin' => $fechaFin
        ]);

        // Registrar en historial
        HistorialModeracion::registrarAccion(
            Auth::id(),
            'bloquear_usuario',
            $request->motivo,
            $request->detalles,
            $alumnoId,
            $bloqueo
        );

        return redirect()->back()->with('success', 'Usuario bloqueado correctamente');
    }

    /**
     * Lista de usuarios bloqueados
     */
    public function usuariosBloqueados()
    {
        $bloqueados = UsuarioBloqueado::with(['alumno', 'bloqueadoPor'])
            ->activos()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('moderacion.bloqueados.index', compact('bloqueados'));
    }

    /**
     * Desbloquear usuario
     */
    public function desbloquearUsuario(Request $request, $id)
    {
        $request->validate([
            'razon_levantamiento' => 'required|string|max:500'
        ]);

        $bloqueo = UsuarioBloqueado::findOrFail($id);
        $bloqueo->levantar(Auth::id(), $request->razon_levantamiento);

        return redirect()->back()->with('success', 'Usuario desbloqueado correctamente');
    }

    /**
     * Historial de moderación
     */
    public function historial(Request $request)
    {
        $query = HistorialModeracion::with(['moderador', 'alumnoAfectado']);

        if ($request->moderador_id) {
            $query->where('moderador_id', $request->moderador_id);
        }

        if ($request->accion) {
            $query->where('accion', $request->accion);
        }

        if ($request->fecha_desde) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $historial = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('moderacion.historial.index', compact('historial'));
    }

    /**
     * Estadísticas de moderación
     */
    public function estadisticas()
    {
        $estadisticasGenerales = [
            'total_reportes' => Reporte::count(),
            'reportes_pendientes' => Reporte::pendientes()->count(),
            'reportes_resueltos' => Reporte::where('estado', 'resuelto')->count(),
            'usuarios_bloqueados_activos' => UsuarioBloqueado::activos()->count(),
            'publicaciones_eliminadas' => HistorialModeracion::where('accion', 'eliminar_publicacion')->count(),
        ];

        $reportesPorMes = Reporte::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $moderadoresMasActivos = HistorialModeracion::with('moderador')
            ->selectRaw('moderador_id, COUNT(*) as total_acciones')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('moderador_id')
            ->orderBy('total_acciones', 'desc')
            ->limit(10)
            ->get();

        return view('moderacion.estadisticas.index', compact(
            'estadisticasGenerales',
            'reportesPorMes',
            'moderadoresMasActivos'
        ));
    }

    /**
     * Ejecutar acción de moderación
     */
    private function ejecutarAccion($accion, $reporte, $request)
    {
        switch ($accion) {
            case 'eliminacion_contenido':
                if ($reporte->reportable_type === 'App\\Models\\Publicacion') {
                    $this->eliminarPublicacionInterno($reporte->reportable, 'Eliminado por reporte');
                } elseif ($reporte->reportable_type === 'App\\Models\\Comentario') {
                    $this->eliminarComentarioInterno($reporte->reportable, 'Eliminado por reporte');
                }
                break;

            case 'bloqueo_temporal':
            case 'bloqueo_permanente':
                $alumnoId = null;
                if ($reporte->reportable && method_exists($reporte->reportable, 'perfil')) {
                    $alumnoId = $reporte->reportable->perfil->alumno_id;
                } elseif ($reporte->reportable_type === 'App\\Models\\Perfil') {
                    $alumnoId = $reporte->reportable->alumno_id;
                }

                if ($alumnoId && !UsuarioBloqueado::estaAlumnoBloqueado($alumnoId)) {
                    $fechaFin = $accion === 'bloqueo_temporal' ? now()->addDays($request->dias_bloqueo ?? 7) : null;
                    
                    UsuarioBloqueado::create([
                        'alumno_id' => $alumnoId,
                        'bloqueado_por' => Auth::id(),
                        'tipo_bloqueo' => $accion === 'bloqueo_temporal' ? 'temporal' : 'permanente',
                        'motivo' => 'Bloqueo por reporte: ' . $reporte->tipo_reporte,
                        'detalles' => $request->respuesta_moderador,
                        'fecha_inicio' => now(),
                        'fecha_fin' => $fechaFin
                    ]);
                }
                break;
        }
    }

    /**
     * Eliminar publicación internamente
     */
    private function eliminarPublicacionInterno($publicacion, $motivo)
    {
        HistorialModeracion::registrarAccion(
            Auth::id(),
            'eliminar_publicacion',
            $motivo,
            null,
            $publicacion->perfil->alumno_id,
            $publicacion,
            [
                'contenido_original' => $publicacion->contenido,
                'archivos' => $publicacion->archivos
            ]
        );

        $publicacion->delete();
    }

    /**
     * Eliminar comentario internamente
     */
    private function eliminarComentarioInterno($comentario, $motivo)
    {
        HistorialModeracion::registrarAccion(
            Auth::id(),
            'eliminar_comentario',
            $motivo,
            null,
            $comentario->perfil->alumno_id,
            $comentario,
            ['contenido_original' => $comentario->contenido]
        );

        $comentario->delete();
    }
}
