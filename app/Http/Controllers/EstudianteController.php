<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Sala;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
