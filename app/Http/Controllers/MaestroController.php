<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Calificacion;
use App\Models\Materia;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MaestroController extends Controller
{
    public function dashboard()
    {
        $maestroId = Auth::id();
        
        // Estadísticas generales
        $totalTareas = Tarea::where('maestro_id', $maestroId)->count();
        $tareasActivas = Tarea::where('maestro_id', $maestroId)->activas()->count();
        $tareasVencidas = Tarea::where('maestro_id', $maestroId)->vencidas()->count();
        $totalCalificaciones = Calificacion::where('maestro_id', $maestroId)->count();
        
        // Tareas próximas a vencer (en los próximos 7 días)
        $tareasProximasVencer = Tarea::where('maestro_id', $maestroId)
            ->where('estado', 'activa')
            ->whereBetween('fecha_entrega', [now(), now()->addDays(7)])
            ->orderBy('fecha_entrega', 'asc')
            ->limit(5)
            ->get();
        
        // Tareas recientes
        $tareasRecientes = Tarea::where('maestro_id', $maestroId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Calificaciones pendientes (tareas entregadas sin calificar)
        $calificacionesPendientes = Tarea::where('maestro_id', $maestroId)
            ->where('estado', 'activa')
            ->whereDoesntHave('calificaciones')
            ->count();
        
        // Materias del maestro
        $materias = Materia::whereHas('tareas', function($query) use ($maestroId) {
            $query->where('maestro_id', $maestroId);
        })->distinct()->get();
        
        return view('maestros.dashboard', compact(
            'totalTareas',
            'tareasActivas',
            'tareasVencidas',
            'totalCalificaciones',
            'tareasProximasVencer',
            'tareasRecientes',
            'calificacionesPendientes',
            'materias'
        ));
    }
    
    public function tareas()
    {
        $maestroId = Auth::id();
        $tareas = Tarea::where('maestro_id', $maestroId)
            ->with(['materia'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('maestros.tareas.index', compact('tareas'));
    }
    
    public function calificaciones()
    {
        $maestroId = Auth::id();
        $calificaciones = Calificacion::where('maestro_id', $maestroId)
            ->with(['alumno', 'materia', 'tarea'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('maestros.calificaciones.index', compact('calificaciones'));
    }
}
