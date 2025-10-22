<?php

namespace App\Http\Controllers;

use App\Models\Carrusel;
use App\Models\Calificacion;
use App\Models\Alumno;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class alumnos_userController extends Controller
{
    public function index(Request $request)
    {
        $carrusels = Carrusel::all();
        
        // Inicializar estadísticas por defecto
        $estadisticas = [
            'promedio_general' => 0,
            'materias_cursadas' => 0,
            'materias_aprobadas' => 0,
            'materias_pendientes' => 0,
            'periodo_actual' => '2024-2025-1'
        ];
        
        // Obtener alumno autenticado
        $alumno = null;
        if (Auth::guard('alumno')->check()) {
            $alumno = Auth::guard('alumno')->user();
        } elseif (Auth::check()) {
            $alumno = Alumno::where('email', Auth::user()->email)->first();
        }
        
        // Calcular estadísticas reales si hay alumno
        if ($alumno) {
            // Promedio general de todas las calificaciones
            $estadisticas['promedio_general'] = Calificacion::where('alumno_id', $alumno->id)
                ->avg('calificacion') ?? 0;
            
            // Materias únicas cursadas
            $estadisticas['materias_cursadas'] = Calificacion::where('alumno_id', $alumno->id)
                ->distinct('materia_id')
                ->count();
            
            // Materias aprobadas (promedio >= 70)
            $estadisticas['materias_aprobadas'] = Calificacion::where('alumno_id', $alumno->id)
                ->select('materia_id')
                ->groupBy('materia_id')
                ->havingRaw('AVG(calificacion) >= 70')
                ->count();
            
            // Materias pendientes/reprobadas
            $estadisticas['materias_pendientes'] = $estadisticas['materias_cursadas'] - $estadisticas['materias_aprobadas'];
            
            // Obtener materias del semestre actual del alumno que no tiene calificaciones
            $materiasSemestre = Materia::where('semestre', $alumno->semestre)
                ->where(function($query) use ($alumno) {
                    $query->where('especialidad', $alumno->especialidad)
                          ->orWhere('especialidad', 'tronco comun');
                })
                ->count();
            
            // Sumar materias del semestre que no han sido cursadas
            $materiasSinCursar = $materiasSemestre - $estadisticas['materias_cursadas'];
            if ($materiasSinCursar > 0) {
                $estadisticas['materias_pendientes'] += $materiasSinCursar;
            }
        }
        
        return view('alumnos_user.index', compact('carrusels', 'estadisticas', 'alumno'));
    }
}