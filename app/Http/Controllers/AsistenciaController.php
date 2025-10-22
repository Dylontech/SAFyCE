<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\ComprobanteAsistencia;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
    // Registrar entrada en plantel por código de barras
    public function registrarPlantel(Request $request)
    {
        $data = $request->validate([
            'codigo_barra' => 'required|string',
            'fecha' => 'nullable|date',
            'hora' => 'nullable',
        ]);

        $alumno = Alumno::where('codigo_barra', $data['codigo_barra'])->first();
        if (! $alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }

        $grupoId = null;
        if ($alumno->Grupo) {
            if (is_numeric($alumno->Grupo)) {
                $g = \App\Models\Grupo::find((int) $alumno->Grupo);
                $grupoId = $g ? $g->id : null;
            } else {
                $g = \App\Models\Grupo::where('nombre_completo', $alumno->Grupo)->first();
                $grupoId = $g ? $g->id : null;
            }
        }

        $asistencia = Asistencia::create([
            'alumno_id' => $alumno->id,
            'nivel' => 'plantel',
            'fecha' => $data['fecha'] ?? now()->toDateString(),
            'hora_entrada' => $data['hora'] ?? now()->toTimeString(),
            'codigo_barra' => $data['codigo_barra'],
            'grupo_id' => $grupoId,
            'estado' => 'presente',
        ]);

        return response()->json(['ok' => true, 'asistencia' => $asistencia]);
    }

    // Vista lista de alumnos del salon para el maestro
    public function listaSalon(Request $request)
    {
        $user = Auth::user();
        // asumir que el maestro tiene asignado grupo_id en profile o relacion

        $grupoId = $request->query('grupo_id') ?? ($user->grupo_id ?? $request->query('Grupo') ?? null);

        $alumnos = Alumno::when($grupoId, function ($q) use ($grupoId) {
            return $q->where('Grupo', $grupoId);
        })->get();

        return view('asistencias.salon', compact('alumnos', 'grupoId'));
    }

    // Registrar asistencia desde salon (maestro)
    public function registrarSalon(Request $request)
    {
        // Support batch submission: estado[alumno_id] => estado
        $data = $request->validate([
            'nivel' => 'required|in:plantel,salon',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|in:presente,falta,justificada',
            'motivo' => 'nullable|string',
            'estado.*' => 'nullable|in:presente,falta,justificada',
        ]);

        $fecha = $data['fecha'] ?? now()->toDateString();

        // If batch: request has 'estado' as array with keys alumno ids
        $estados = $request->input('estado');
        if (is_array($estados)) {
            foreach ($estados as $alumnoId => $estadoVal) {
                if (! in_array($estadoVal, ['presente','falta','justificada'])) continue;
                $alumno = Alumno::find($alumnoId);
                if (! $alumno) continue;

                // resolve grupo for this alumno
                $grupoId = null;
                if ($alumno->Grupo) {
                    if (is_numeric($alumno->Grupo)) {
                        $g = \App\Models\Grupo::find((int) $alumno->Grupo);
                        $grupoId = $g ? $g->id : null;
                    } else {
                        $g = \App\Models\Grupo::where('nombre_completo', $alumno->Grupo)->first();
                        $grupoId = $g ? $g->id : null;
                    }
                }

                Asistencia::create([
                    'alumno_id' => $alumno->id,
                    'grupo_id' => $grupoId,
                    'maestro_id' => Auth::id(),
                    'nivel' => $data['nivel'],
                    'fecha' => $fecha,
                    'hora_entrada' => now()->toTimeString(),
                    'estado' => $estadoVal,
                    'motivo' => $request->input('motivo_'.$alumnoId) ?? null,
                ]);
            }
            return back()->with('success', 'Asistencias registradas');
        }

        // Single registro fallback
        $alumnoId = $request->input('alumno_id');
        if ($alumnoId) {
            $alumno = Alumno::find($alumnoId);
            if ($alumno) {
                $grupoId = null;
                if ($alumno->Grupo) {
                    if (is_numeric($alumno->Grupo)) {
                        $g = \App\Models\Grupo::find((int) $alumno->Grupo);
                        $grupoId = $g ? $g->id : null;
                    } else {
                        $g = \App\Models\Grupo::where('nombre_completo', $alumno->Grupo)->first();
                        $grupoId = $g ? $g->id : null;
                    }
                }

                Asistencia::create([
                    'alumno_id' => $alumno->id,
                    'grupo_id' => $grupoId,
                    'maestro_id' => Auth::id(),
                    'nivel' => $data['nivel'],
                    'fecha' => $fecha,
                    'hora_entrada' => now()->toTimeString(),
                    'estado' => $data['estado'] ?? 'presente',
                    'motivo' => $data['motivo'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Asistencia registrada');
    }

    // Historial alumno
    public function historialAlumno($alumnoId)
    {
        $alumno = Alumno::findOrFail($alumnoId);
        $asistencias = Asistencia::where('alumno_id', $alumnoId)->orderBy('fecha', 'desc')->get();
        $comprobantes = ComprobanteAsistencia::where('alumno_id', $alumnoId)->orderBy('created_at', 'desc')->get();

        return view('asistencias.historial', compact('alumno', 'asistencias', 'comprobantes'));
    }

    // Historial del alumno autenticado
    public function miHistorial(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        if (! $alumno) {
            abort(403);
        }

        $asistencias = Asistencia::where('alumno_id', $alumno->id)->orderBy('fecha', 'desc')->get();
        $comprobantes = ComprobanteAsistencia::where('alumno_id', $alumno->id)->orderBy('created_at', 'desc')->get();

        return view('asistencias.historial', ['alumno' => $alumno, 'asistencias' => $asistencias, 'comprobantes' => $comprobantes]);
    }

    // Subir comprobante por alumno
    public function subirComprobante(Request $request)
    {
        $data = $request->validate([
            'asistencia_id' => 'nullable|exists:asistencias,id',
            'alumno_id' => 'required|exists:alumnos,id',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('archivo')->store('comprobantes', 'public');

        $comp = ComprobanteAsistencia::create([
            'asistencia_id' => $data['asistencia_id'] ?? null,
            'alumno_id' => $data['alumno_id'],
            'archivo' => $path,
            'estado' => 'pendiente',
        ]);

        return back()->with('success', 'Comprobante subido');
    }

    // Aprobar o rechazar comprobante (maestro/administrador)
    public function revisarComprobante(Request $request, $id)
    {
        $comp = ComprobanteAsistencia::findOrFail($id);
        $data = $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string',
        ]);

        $comp->update([
            'estado' => $data['estado'],
            'comentario' => $data['comentario'] ?? null,
            'revisado_por' => Auth::id(),
        ]);

        // Si aprobado, actualizar la asistencia relacionada como justificada
        if ($data['estado'] === 'aprobado' && $comp->asistencia_id) {
            $as = $comp->asistencia;
            if ($as) {
                $as->update(['estado' => 'justificada']);
            }
        }

        return back()->with('success', 'Comprobante actualizado');
    }

    // Reporte simple: exportar asistencias por fecha o rango
    public function reporte(Request $request)
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $query = Asistencia::query();
        if ($desde) $query->where('fecha', '>=', $desde);
        if ($hasta) $query->where('fecha', '<=', $hasta);

        $asistencias = $query->with('alumno')->orderBy('fecha', 'desc')->get();

        // Retornar vista que puede imprimirse
        return view('asistencias.reporte', compact('asistencias', 'desde', 'hasta'));
    }
}
