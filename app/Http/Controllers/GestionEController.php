<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormularioE;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GestionEController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los filtros de la solicitud
        $search = $request->input('search');
        $especialidad = $request->input('especialidad');
        $grupo = $request->input('grupo');
        $tipo_pago = $request->input('tipo_pago');
    
        // Construir la consulta con los filtros
        $query = FormularioE::query();
    
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%$search%")
                  ->orWhere('numero_control', 'LIKE', "%$search%")
                  ->orWhere('curp', 'LIKE', "%$search%");
            });
        }
    
        if ($especialidad) {
            $query->where('especialidad', $especialidad);
        }
    
        if ($grupo) {
            $query->where('grupo', $grupo);
        }
    
        if ($tipo_pago) {
            $query->where('tipo_pago', $tipo_pago);
        }
    
        $formularios = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));

        // Obtener las listas para los filtros
        $especialidades = FormularioE::distinct()->pluck('especialidad');
        $grupos = FormularioE::distinct()->pluck('grupo');
        $tipo_pagos = FormularioE::distinct()->pluck('tipo_pago');

        // Mensajes informativos
        $message = null;
        $messageType = null;

        if ($request->filled('search') && $formularios->isEmpty()) {
            $message = "No se encontraron solicitudes con los criterios de búsqueda: '{$request->search}'";
            $messageType = 'warning';
        } elseif ($request->filled('search')) {
            $message = "Búsqueda realizada por: '{$request->search}' - Se encontraron {$formularios->total()} resultados";
            $messageType = 'info';
        } elseif ($request->filled('especialidad') && $formularios->isEmpty()) {
            $message = "No se encontraron solicitudes en la especialidad: '{$request->especialidad}'";
            $messageType = 'warning';
        } elseif ($request->filled('especialidad')) {
            $message = "Filtrado por especialidad: '{$request->especialidad}' - Se encontraron {$formularios->total()} resultados";
            $messageType = 'info';
        } elseif ($request->filled('grupo') && $formularios->isEmpty()) {
            $message = "No se encontraron solicitudes en el grupo: '{$request->grupo}'";
            $messageType = 'warning';
        } elseif ($request->filled('grupo')) {
            $message = "Filtrado por grupo: '{$request->grupo}' - Se encontraron {$formularios->total()} resultados";
            $messageType = 'info';
        } elseif ($request->filled('tipo_pago') && $formularios->isEmpty()) {
            $message = "No se encontraron solicitudes con el tipo de pago: '{$request->tipo_pago}'";
            $messageType = 'warning';
        } elseif ($request->filled('tipo_pago')) {
            $message = "Filtrado por tipo de pago: '{$request->tipo_pago}' - Se encontraron {$formularios->total()} resultados";
            $messageType = 'info';
        }

        return view('Control_user.index', compact('formularios', 'especialidades', 'grupos', 'tipo_pagos', 'message', 'messageType'));
    }

    public function show($id)
    {
        try {
            $formulario = FormularioE::findOrFail($id);
            $ligaDePagoExiste = $formulario->liga_de_pago && Storage::exists($formulario->liga_de_pago);
            $comprobanteExiste = $formulario->comprobante && Storage::exists($formulario->comprobante);
            $comprobanteAlumnoExiste = $formulario->comprobante_alumno && Storage::exists($formulario->comprobante_alumno);
            $comprobanteOficialExiste = $formulario->comprobante_oficial && Storage::exists($formulario->comprobante_oficial);

            return view('Control_user.showE', compact('formulario', 'ligaDePagoExiste', 'comprobanteExiste', 'comprobanteAlumnoExiste', 'comprobanteOficialExiste'));
        } catch (\Exception $e) {
            return redirect()->route('control_user.index')
                ->with('error', 'Solicitud no encontrada.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string|max:255',
                'comentario' => 'nullable|string',
                'comentario_financiero' => 'nullable|string'
            ]);

            $formulario = FormularioE::findOrFail($id);
            $formulario->status = $request->input('status');
            $formulario->comentario = $request->input('comentario');
            $formulario->comentario_financiero = $request->input('comentario_financiero');
            $formulario->save();

            return redirect()->route('control_user.index')->with('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    public function uploadComprobante(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comprobante' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al subir el comprobante.');
        }

        try {
            $formulario = FormularioE::findOrFail($id);
            if ($request->hasFile('comprobante')) {
                $filePath = $request->file('comprobante')->store('public/comprobantes');
                $formulario->comprobante = $filePath;
                $formulario->save();
            }

            return redirect()->route('control_user.index')->with('success', 'Comprobante subido correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir el comprobante: ' . $e->getMessage());
        }
    }

    public function downloadComprobante($id, $type)
    {
        try {
            $formulario = FormularioE::findOrFail($id);
            $filePath = $formulario->$type;

            if (!$filePath || !Storage::exists($filePath)) {
                return redirect()->back()->with('error', 'El archivo no existe.');
            }

            return response()->download(storage_path('app/' . $filePath));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar el archivo.');
        }
    }

    public function indexServicios(Request $request)
    {
        $search = $request->input('buscar');
        $especialidad = $request->input('especialidad');
        $grupo = $request->input('grupo');
        
        $query = FormularioE::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%$search%")
                  ->orWhere('numero_control', 'LIKE', "%$search%")
                  ->orWhere('curp', 'LIKE', "%$search%");
            });
        }

        if ($especialidad) {
            $query->where('especialidad', $especialidad);
        }

        if ($grupo) {
            $query->where('grupo', $grupo);
        }

        $formularios = $query->orderBy('created_at', 'desc')->paginate(10);

        $controles = FormularioE::distinct()->pluck('numero_control');
        $especialidades = FormularioE::distinct()->pluck('especialidad');
        $grupos = FormularioE::distinct()->pluck('grupo');
        
        return view('financiero_user.SolicitudesServiciosS', compact('formularios', 'controles', 'especialidades', 'grupos'));
    }

    public function indexComprobantes($id)
    {
        try {
            $formulario = FormularioE::findOrFail($id);
        
            $ligaDePagoExiste = $formulario->liga_de_pago && Storage::exists($formulario->liga_de_pago);
            $comprobanteExiste = $formulario->comprobante && Storage::exists($formulario->comprobante);
            $comprobanteAlumnoExiste = $formulario->comprobante_alumno && Storage::exists($formulario->comprobante_alumno);
            $comprobanteOficialExiste = $formulario->comprobante_oficial && Storage::exists($formulario->comprobante_oficial);
        
            return view('financiero_user.comprobantesE', compact('formulario', 'ligaDePagoExiste', 'comprobanteExiste', 'comprobanteAlumnoExiste', 'comprobanteOficialExiste'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Solicitud no encontrada.');
        }
    }

    public function uploadLigaDePago(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'liga_de_pago' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al subir la liga de pago.');
        }

        try {
            $formulario = FormularioE::findOrFail($id);
            if ($request->hasFile('liga_de_pago')) {
                $filePath = $request->file('liga_de_pago')->store('public/liga_de_pago');
                $formulario->liga_de_pago = $filePath;
                $formulario->save();
            }

            return redirect()->route('solicitudes-servicios-s.index')->with('success', 'Liga de pago subida correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir la liga de pago: ' . $e->getMessage());
        }
    }

    public function downloadLigaDePago($id)
    {
        return $this->downloadFile($id, 'liga_de_pago', 'public/liga_de_pago');
    }

    public function uploadComprobanteAlumno(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comprobante_alumno' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al subir el comprobante del alumno.');
        }

        try {
            $formulario = FormularioE::findOrFail($id);
            if ($request->hasFile('comprobante_alumno')) {
                $file = $request->file('comprobante_alumno');
                $filePath = $file->store('public/comprobantes_alumno');
                $formulario->comprobante_alumno = $filePath;
                $formulario->save();
                return redirect()->back()->with('success', 'Comprobante del alumno subido correctamente.');
            }

            return redirect()->back()->with('error', 'Error al subir el comprobante.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir el comprobante del alumno: ' . $e->getMessage());
        }
    }

    public function downloadComprobanteAlumno($id)
    {
        return $this->downloadFile($id, 'comprobante_alumno', 'public/comprobantes_alumno');
    }

    private function downloadFile($id, $fileType, $directory)
    {
        try {
            $formulario = FormularioE::findOrFail($id);
            $filePath = $formulario->$fileType;

            if (!$filePath || !Storage::exists($filePath)) {
                return redirect()->back()->with('error', 'El archivo no existe.');
            }

            return Storage::download($filePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar el archivo.');
        }
    }

    public function uploadComprobanteOficial(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comprobante_oficial' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al subir el comprobante oficial.');
        }

        try {
            $formulario = FormularioE::findOrFail($id);
            if ($request->hasFile('comprobante_oficial')) {
                $filePath = $request->file('comprobante_oficial')->store('public/comprobantes_oficiales');
                $formulario->comprobante_oficial = $filePath;
                $formulario->save();
            }

            return redirect()->back()->with('success', 'Comprobante oficial subido correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir el comprobante oficial: ' . $e->getMessage());
        }
    }

    public function downloadComprobanteOficial($id)
    {
        return $this->downloadFile($id, 'comprobante_oficial', 'public/comprobantes_oficiales');
    }

    public function uploadStudentReceipt(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comprobante' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al subir el comprobante.');
        }

        try {
            $formulario = FormularioE::findOrFail($id);
            if ($request->hasFile('comprobante')) {
                $filePath = $request->file('comprobante')->store('public/comprobantes');
                $formulario->comprobante = $filePath;
                $formulario->save();
            }

            return redirect()->back()->with('success', 'Comprobante subido correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir el comprobante: ' . $e->getMessage());
        }
    }

    public function downloadStudentReceipt($id)
    {
        try {
            $formulario = FormularioE::findOrFail($id);
            $filePath = $formulario->comprobante;

            if (!$filePath || !Storage::exists($filePath)) {
                return redirect()->back()->with('error', 'El archivo no existe.');
            }

            return Storage::download($filePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar el archivo.');
        }
    }
}