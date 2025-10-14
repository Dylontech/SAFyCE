<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Materia;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
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
        Gate::authorize('ver horarios');

        $query = Horario::with(['materia', 'maestro', 'sala']);

        // Filtros
        if ($request->filled('dia_semana')) {
            $query->where('dia_semana', $request->dia_semana);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Si es maestro, solo mostrar sus horarios
        if (Auth::user()->esMaestro() && !Auth::user()->esAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $horarios = $query->orderBy('dia_semana')
                         ->orderBy('hora_inicio')
                         ->paginate(15);

        // Datos para filtros
        $maestros = User::role('maestro')->get();
        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

        return view('horarios.index', compact('horarios', 'maestros', 'dias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('crear horarios');

        $materias = Materia::orderBy('materia')->get();
        $maestros = User::role('maestro')->get();
        $salas = Sala::where('estado', 'disponible')->orderBy('nombre')->get();
        
        return view('horarios.create', compact('materias', 'maestros', 'salas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('crear horarios');

        $request->validate(Horario::$rules);

        // Verificar disponibilidad de la sala
        $sala = Sala::find($request->sala_id);
        if (!$sala->estaDisponible($request->dia_semana, $request->hora_inicio, $request->hora_fin)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'La sala no está disponible en el horario seleccionado.');
        }

        // Verificar que el maestro no tenga otro horario al mismo tiempo
        $conflictoMaestro = Horario::where('user_id', $request->user_id)
                                  ->where('dia_semana', $request->dia_semana)
                                  ->where('fecha_inicio', '<=', $request->fecha_fin)
                                  ->where('fecha_fin', '>=', $request->fecha_inicio)
                                  ->where(function ($query) use ($request) {
                                      $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                                            ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                                            ->orWhere(function ($q) use ($request) {
                                                $q->where('hora_inicio', '<', $request->hora_inicio)
                                                  ->where('hora_fin', '>', $request->hora_fin);
                                            });
                                  })->exists();

        if ($conflictoMaestro) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'El maestro ya tiene una clase asignada en ese horario.');
        }

        $horario = Horario::create($request->all());

        return redirect()->route('horarios.index')
                        ->with('success', 'Horario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        Gate::authorize('ver horarios');

        $horario->load(['materia', 'maestro', 'sala']);

        return view('horarios.show', compact('horario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        Gate::authorize('editar horarios');

        $materias = Materia::orderBy('materia')->get();
        $maestros = User::role('maestro')->get();
        $salas = Sala::where('estado', 'disponible')->orderBy('nombre')->get();

        return view('horarios.edit', compact('horario', 'materias', 'maestros', 'salas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        Gate::authorize('editar horarios');

        $request->validate(Horario::$rules);

        // Verificar disponibilidad de la sala (excluyendo el horario actual)
        $sala = Sala::find($request->sala_id);
        if (!$sala->estaDisponible($request->dia_semana, $request->hora_inicio, $request->hora_fin, $horario->id)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'La sala no está disponible en el horario seleccionado.');
        }

        // Verificar que el maestro no tenga otro horario al mismo tiempo (excluyendo el actual)
        $conflictoMaestro = Horario::where('user_id', $request->user_id)
                                  ->where('id', '!=', $horario->id)
                                  ->where('dia_semana', $request->dia_semana)
                                  ->where('fecha_inicio', '<=', $request->fecha_fin)
                                  ->where('fecha_fin', '>=', $request->fecha_inicio)
                                  ->where(function ($query) use ($request) {
                                      $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                                            ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                                            ->orWhere(function ($q) use ($request) {
                                                $q->where('hora_inicio', '<', $request->hora_inicio)
                                                  ->where('hora_fin', '>', $request->hora_fin);
                                            });
                                  })->exists();

        if ($conflictoMaestro) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'El maestro ya tiene una clase asignada en ese horario.');
        }

        $horario->update($request->all());

        return redirect()->route('horarios.index')
                        ->with('success', 'Horario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        Gate::authorize('eliminar horarios');

        $horario->delete();

        return redirect()->route('horarios.index')
                        ->with('success', 'Horario eliminado exitosamente.');
    }

    /**
     * Mostrar horario por grupo
     */
    public function porGrupo($grupo)
    {
        Gate::authorize('ver horarios');

        $horarios = Horario::with(['materia', 'maestro', 'sala'])
                          ->activos()
                          ->orderBy('dia_semana')
                          ->orderBy('hora_inicio')
                          ->get();

        return view('horarios.grupo', compact('horarios', 'grupo'));
    }

    /**
     * Mostrar horario del maestro
     */
    public function miHorario()
    {
        if (!Auth::user()->esMaestro()) {
            abort(403, 'No autorizado');
        }

        $horarios = Horario::with(['materia', 'sala'])
                          ->where('user_id', Auth::id())
                          ->activos()
                          ->orderBy('dia_semana')
                          ->orderBy('hora_inicio')
                          ->get();

        return view('horarios.mi-horario', compact('horarios'));
    }
}
