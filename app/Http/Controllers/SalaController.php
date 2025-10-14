<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        return view('salas.show', compact('sala', 'horarios'));
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
}
