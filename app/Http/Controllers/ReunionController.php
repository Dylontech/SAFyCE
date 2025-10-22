<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReunionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:alumno');
    }

    /**
     * Mostrar reuniones disponibles para alumnos
     */
    public function index()
    {
        $reunionesHoy = Reunion::with(['sala', 'creador'])
            ->hoy()
            ->activas()
            ->orderBy('hora')
            ->get();

        $reunionesProximas = Reunion::with(['sala', 'creador'])
            ->where('fecha', '>', now()->toDateString())
            ->activas()
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(10)
            ->get();

        return view('alumnos_user.reuniones.index', compact('reunionesHoy', 'reunionesProximas'));
    }

    /**
     * Mostrar una reunión específica
     */
    public function show(Reunion $reunion)
    {
        // Verificar que la reunión esté disponible
        if (!$reunion->puedeUnirse()) {
            return redirect()->route('alumnos.reuniones.index')
                           ->with('error', 'Esta reunión no está disponible en este momento.');
        }

        return view('alumnos_user.reuniones.show', compact('reunion'));
    }

    /**
     * Unirse a una reunión (redirigir al enlace)
     */
    public function unirse(Reunion $reunion)
    {
        // Verificar que la reunión esté disponible
        if (!$reunion->puedeUnirse()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta reunión no está disponible en este momento.'
            ], 403);
        }

        // Incrementar contador de participantes
        $reunion->increment('participantes_actuales');

        return response()->json([
            'success' => true,
            'enlace' => $reunion->enlace_reunion,
            'message' => 'Redirigiendo a la reunión...'
        ]);
    }

    /**
     * API para obtener reuniones del día
     */
    public function reunionesHoy()
    {
        $reuniones = Reunion::with(['sala', 'creador'])
            ->hoy()
            ->activas()
            ->orderBy('hora')
            ->get()
            ->map(function($reunion) {
                return [
                    'id' => $reunion->id,
                    'titulo' => $reunion->titulo,
                    'descripcion' => $reunion->descripcion,
                    'hora' => $reunion->hora_formateada,
                    'duracion' => $reunion->duracion_formateada,
                    'plataforma' => $reunion->plataforma,
                    'plataforma_icono' => $reunion->plataforma_icono,
                    'tipo' => $reunion->tipo,
                    'sala' => $reunion->sala ? $reunion->sala->nombre : 'Virtual',
                    'creador' => $reunion->creador->name,
                    'puede_unirse' => $reunion->puedeUnirse(),
                    'en_curso' => $reunion->estaEnCurso(),
                    'ha_terminado' => $reunion->haTerminado(),
                    'estado_color' => $reunion->estado_color
                ];
            });

        return response()->json($reuniones);
    }

    /**
     * Buscar reuniones
     */
    public function buscar(Request $request)
    {
        $query = Reunion::with(['sala', 'creador'])->activas();

        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('plataforma')) {
            $query->where('plataforma', $request->plataforma);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        $reuniones = $query->orderBy('fecha')
                          ->orderBy('hora')
                          ->paginate(10);

        return response()->json([
            'reuniones' => $reuniones->items(),
            'pagination' => [
                'current_page' => $reuniones->currentPage(),
                'last_page' => $reuniones->lastPage(),
                'total' => $reuniones->total()
            ]
        ]);
    }
}
