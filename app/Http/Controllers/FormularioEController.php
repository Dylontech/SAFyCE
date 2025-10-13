<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormularioE;
use App\Models\Materia;
use Illuminate\Support\Facades\Auth;

class FormularioEController extends Controller
{
    // Mostrar la lista de solicitudes E
    public function solicitudesE(Request $request)
    {
        // Verificar el tipo de usuario
        $esAlumno = Auth::guard('alumno')->check();
        
        if ($esAlumno) {
            // ALUMNO: Solo ver sus propias solicitudes filtrando por numero_control
            $alumno = Auth::guard('alumno')->user();
            $numeroControlAlumno = $alumno->numero_control;

            // Filtrar por numero_control del alumno autenticado
            $query = FormularioE::where('numero_control', $numeroControlAlumno);

            $materias = FormularioE::where('numero_control', $numeroControlAlumno)
                                   ->distinct()
                                   ->pluck('materias')
                                   ->filter()
                                   ->values();
        } else {
            // USUARIOS DEL SISTEMA (admin, control_escolar, servicio_financiero): Ver todas las solicitudes
            $query = FormularioE::query();
            $materias = FormularioE::distinct()->pluck('materias')->filter()->values();
        }

        // Aplicar filtros
        if ($request->filled('materias')) {
            $query->where('materias', 'like', '%' . $request->materias . '%');
        }

        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->tipo_pago);
        }

        if ($request->filled('fecha_pago')) {
            $query->whereDate('fecha_pago', $request->fecha_pago);
        }

        $formularios = $query->paginate(10);

        return view('alumnos_user.solicitudesE', compact('formularios', 'materias'));
    }

    public function create()
    {
        // Verificar que sea alumno
        if (!Auth::guard('alumno')->check()) {
            return redirect()->route('login.alumno')->with('error', 'Debes iniciar sesión como alumno');
        }

        $alumno = Auth::guard('alumno')->user();
        
        // Obtener el semestre y especialidad del alumno
        $semestreAlumno = $alumno->semestre ?? null;
        $especialidadAlumno = $alumno->especialidad ?? null;
        
        // Filtrar materias por semestre y especialidad del alumno
        $materiasQuery = Materia::query();
        
        if ($semestreAlumno) {
            $materiasQuery->where('semestre', $semestreAlumno);
        }
        
        if ($especialidadAlumno) {
            // Buscar coincidencias en la especialidad
            $materiasQuery->where(function($query) use ($especialidadAlumno) {
                $query->where('especialidad', $especialidadAlumno)
                      ->orWhere('especialidad', 'like', '%' . $especialidadAlumno . '%')
                      ->orWhere('especialidad', 'tronco comun');
            });
        }
        
        $materias = $materiasQuery->get();

        return view('alumnos_user.formulario', compact('materias', 'alumno'));
    }

    public function store(Request $request)
    {
        // Verificar que sea alumno
        if (!Auth::guard('alumno')->check()) {
            return redirect()->route('login.alumno')->with('error', 'Debes iniciar sesión como alumno');
        }

        $alumno = Auth::guard('alumno')->user();

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'curp' => 'required|string|max:18',
            'numero_control' => 'required|string|max:10',
            'especialidad' => 'required|string|max:255',
            'numero_lista' => 'required|integer',
            'grupo' => 'required|string|max:10',
            'tipo_pago' => 'required|string|max:255',
            'fecha_pago' => 'required|date',
            'materias' => 'required|array',
            'materias.*' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'comentario' => 'nullable|string|max:255',
            'comentario_financiero' => 'nullable|string|max:255',
            'liga_de_pago' => 'nullable|string|max:255',
            'comprobante_alumno' => 'nullable|string|max:255',
            'comprobante' => 'nullable|string|max:255',
            'comprobante_oficial' => 'nullable|string|max:255',
        ]);

        // Verificar que el numero_control coincida con el alumno autenticado
        if ($validatedData['numero_control'] !== $alumno->numero_control) {
            return redirect()->back()->with('error', 'El número de control no coincide con tu información.')->withInput();
        }

        // PROCESAR COMO TEXTO SIMPLE
        $materiasSeleccionadas = array_filter($validatedData['materias']);
        $materiasTexto = implode(', ', $materiasSeleccionadas);

        $formulario = new FormularioE();
        $formulario->alumno_id = $alumno->id;
        $formulario->nombre = $validatedData['nombre'];
        $formulario->curp = $validatedData['curp'];
        $formulario->numero_control = $validatedData['numero_control'];
        $formulario->especialidad = $validatedData['especialidad'];
        $formulario->numero_lista = $validatedData['numero_lista'];
        $formulario->grupo = $validatedData['grupo'];
        $formulario->tipo_pago = $validatedData['tipo_pago'];
        $formulario->fecha_pago = $validatedData['fecha_pago'];
        $formulario->materias = $materiasTexto;
        $formulario->status = $validatedData['status'] ?? 'pendiente';
        $formulario->comentario = $validatedData['comentario'] ?? '';
        $formulario->comentario_financiero = $validatedData['comentario_financiero'] ?? '';
        $formulario->liga_de_pago = $validatedData['liga_de_pago'] ?? '';
        $formulario->comprobante_alumno = $validatedData['comprobante_alumno'] ?? '';
        $formulario->comprobante = $validatedData['comprobante'] ?? '';
        $formulario->comprobante_oficial = $validatedData['comprobante_oficial'] ?? '';

        $formulario->save();

        return redirect()->route('solicitudesE.index')->with('success', 'Solicitud enviada exitosamente.');
    }

    // Actualizar una solicitud existente
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'curp' => 'required|string|max:18',
            'numero_control' => 'required|string|max:10',
            'especialidad' => 'required|string|max:255',
            'numero_lista' => 'required|integer',
            'grupo' => 'required|string|max:10',
            'tipo_pago' => 'required|string|max:255',
            'fecha_pago' => 'required|date',
            'materias' => 'required|array',
            'materias.*' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'comentario' => 'nullable|string|max:255',
            'comentario_financiero' => 'nullable|string|max:255',
            'liga_de_pago' => 'nullable|string|max:255',
            'comprobante_alumno' => 'nullable|string|max:255',
            'comprobante' => 'nullable|string|max:255',
            'comprobante_oficial' => 'nullable|string|max:255',
        ]);

        $formulario = FormularioE::findOrFail($id);
        
        // Verificar permisos según el tipo de usuario
        $esAlumno = Auth::guard('alumno')->check();
        
        if ($esAlumno) {
            // ALUMNO: Solo puede editar sus propias solicitudes
            $alumno = Auth::guard('alumno')->user();
            if ($formulario->numero_control !== $alumno->numero_control) {
                return redirect()->route('solicitudesE.index')->with('error', 'No tienes permisos para editar esta solicitud.');
            }

            // Verificar que el numero_control actualizado siga coincidiendo con el alumno
            if ($validatedData['numero_control'] !== $alumno->numero_control) {
                return redirect()->back()->with('error', 'El número de control debe coincidir con tu información.')->withInput();
            }
        } else {
            // USUARIOS DEL SISTEMA: Pueden editar cualquier solicitud
            // No se necesita verificación adicional para admin, control_escolar, servicio_financiero
        }
        
        // PROCESAR COMO TEXTO SIMPLE
        $materiasSeleccionadas = array_filter($validatedData['materias']);
        $materiasTexto = implode(', ', $materiasSeleccionadas);

        $formulario->update([
            'nombre' => $validatedData['nombre'],
            'curp' => $validatedData['curp'],
            'numero_control' => $validatedData['numero_control'],
            'especialidad' => $validatedData['especialidad'],
            'numero_lista' => $validatedData['numero_lista'],
            'grupo' => $validatedData['grupo'],
            'tipo_pago' => $validatedData['tipo_pago'],
            'fecha_pago' => $validatedData['fecha_pago'],
            'materias' => $materiasTexto,
            'status' => $validatedData['status'] ?? 'pendiente',
            'comentario' => $validatedData['comentario'] ?? '',
            'comentario_financiero' => $validatedData['comentario_financiero'] ?? '',
            'liga_de_pago' => $validatedData['liga_de_pago'] ?? '',
            'comprobante_alumno' => $validatedData['comprobante_alumno'] ?? '',
            'comprobante' => $validatedData['comprobante'] ?? '',
            'comprobante_oficial' => $validatedData['comprobante_oficial'] ?? '',
        ]);

        return redirect()->route('solicitudesE.index')->with('success', 'Solicitud actualizada exitosamente.');
    }

    // Eliminar una solicitud
    public function destroy($id)
    {
        $formulario = FormularioE::findOrFail($id);
        
        // Verificar permisos según el tipo de usuario
        $esAlumno = Auth::guard('alumno')->check();
        
        if ($esAlumno) {
            // ALUMNO: Solo puede eliminar sus propias solicitudes
            $alumno = Auth::guard('alumno')->user();
            if ($formulario->numero_control !== $alumno->numero_control) {
                return redirect()->route('solicitudesE.index')->with('error', 'No tienes permisos para eliminar esta solicitud.');
            }
        } else {
            // USUARIOS DEL SISTEMA: Pueden eliminar cualquier solicitud
            // No se necesita verificación adicional para admin, control_escolar, servicio_financiero
        }

        $formulario->delete();

        return redirect()->route('solicitudesE.index')->with('success', 'Solicitud eliminada exitosamente.');
    }
}