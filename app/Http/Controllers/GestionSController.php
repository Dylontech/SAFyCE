<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formulario;
use App\Models\Especialidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GestionSController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Obtener los filtros de la solicitud
        $especialidad = $request->input('especialidad');
        $grupo = $request->input('grupo');
        $control = $request->input('control');
        $tipo_servicio = $request->input('tipo_servicio');
        $buscar = $request->input('buscar');

        // Obtener las listas para los filtros
        $especialidadesDB = Especialidade::orderBy('nombre')->get();
        $grupos = Formulario::distinct()->pluck('grupo');
        $controles = Formulario::distinct()->pluck('control');
        $tipos_servicio = Formulario::distinct()->pluck('tipo_servicio');

        // Construir la consulta con los filtros
        $query = Formulario::query();// posible conflicto con las vistas de los alumnos

        if ($especialidad) {
            $query->where('especialidad', $especialidad);
        }

        if ($grupo) {
            $query->where('grupo', $grupo);
        }

        if ($control) {
            $query->where('control', $control);
        }

        if ($tipo_servicio) {
            $query->where('tipo_servicio', $tipo_servicio);
        }

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%$buscar%")
                  ->orWhere('control', 'LIKE', "%$buscar%")
                  ->orWhere('curp', 'LIKE', "%$buscar%")
                  ->orWhere('tipo_servicio', 'LIKE', "%$buscar%");
            });
        }

        $formularios = $query->paginate(10)->appends($request->except('page'));

        foreach ($formularios as $formulario) {
            $this->revisarYActualizarEstatus($formulario);
        }

        return view('Control_user.GestionS', compact('formularios', 'especialidadesDB', 'grupos', 'controles', 'tipos_servicio'));
    }


    public function updateStatus(Request $request, $id)
    {
        $formulario = Formulario::findOrFail($id);
        $formulario->status = $request->input('status');
        $formulario->comentario = $request->input('comentario');
        $formulario->comentario_financiero = $request->input('comentario_financiero'); // Añadido
        $formulario->save();

        return redirect()->route('Control_user.GestionS')->with('success', 'Estado actualizado correctamente.');
    }

    public function show($id)
    {
        $formulario = Formulario::findOrFail($id);

        $ligaDePagoExiste = Storage::exists('public/' . $formulario->liga_de_pago);
        $comprobanteExiste = Storage::exists('public/' . $formulario->comprobante);
        $comprobanteAlumnoExiste = Storage::exists('public/' . $formulario->comprobante_alumno);
        $comprobanteOficialExiste = Storage::exists('public/' . $formulario->comprobante_oficial); // Añadido

        return view('Control_user.show', compact('formulario', 'ligaDePagoExiste', 'comprobanteExiste', 'comprobanteAlumnoExiste', 'comprobanteOficialExiste')); // Añadido
    }

    private function revisarYActualizarEstatus($formulario)
    {
        $tieneLigaDePago = !is_null($formulario->liga_de_pago);
        $tieneComprobante = !is_null($formulario->comprobante);

        if ($tieneLigaDePago && $tieneComprobante) {
            $formulario->status = 'subir comprobante';
            $formulario->save();
        }
    }

    public function uploadComprobante(Request $request, $id)
    {
        // Validación de tipos de archivo permitidos
        $validator = Validator::make($request->all(), [
            'comprobante' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240', // Tamaño actualizado
            'comentario_financiero' => 'nullable|string', // Añadido
            'comprobante_oficial' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240', // Añadido
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al subir el comprobante.');
        }

        $formulario = Formulario::findOrFail($id);
        if ($request->hasFile('comprobante')) {
            $filePath = $request->file('comprobante')->store('public/comprobantes');
            $formulario->comprobante = $filePath;

            if ($request->hasFile('comprobante_oficial')) {
                $fileOficial = $request->file('comprobante_oficial');
                $pathOficial = $fileOficial->store('public/comprobantes');
                $formulario->comprobante_oficial = $pathOficial;
            }

            $formulario->comentario_financiero = $request->input('comentario_financiero'); // Añadido
            $formulario->save();
        }

        return redirect()->route('gestions.index')->with('success', 'Comprobante subido correctamente.');
    }

    public function downloadComprobanteAlumno($id)
    {
        return $this->downloadFile($id, 'comprobante_alumno');
    }

    public function downloadComprobanteOficial($id) // Añadido
    {
        return $this->downloadFile($id, 'comprobante_oficial');
    }

    public function downloadComprobante($id) // Añadido
    {
        return $this->downloadFile($id, 'comprobante');
    }

    private function downloadFile($id, $fileType)
    {
        $formulario = Formulario::findOrFail($id);

        // Obtener solo el nombre del archivo
        $fileName = basename($formulario->$fileType);

        // Directorio base donde buscar
        $directorioBase = storage_path('app');
        $rutaArchivo = $this->buscarArchivoRecursivamente($directorioBase, $fileName);

        Log::info("Buscando el archivo: $fileName en el directorio: $directorioBase");

        if (!$rutaArchivo) {
            Log::error("El archivo no existe: $fileName en $directorioBase");
            return redirect()->back()->with('error', 'El archivo no existe.');
        }

        if (!$this->archivoValido($rutaArchivo)) {
            Log::error("Tipo de archivo no permitido: $rutaArchivo");
            return redirect()->back()->with('error', 'Tipo de archivo no permitido.');
        }

        return response()->download($rutaArchivo);
    }

    private function archivoValido($filePath)
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $mimeType = mime_content_type($filePath);

        return in_array($mimeType, $allowedMimeTypes);
    }

    private function buscarArchivoRecursivamente($directorio, $nombreArchivo)
    {
        $archivos = File::allFiles($directorio);

        foreach ($archivos as $archivo) {
            if ($archivo->getFilename() == $nombreArchivo) {
                Log::info('Archivo encontrado en: ' . $archivo->getRealPath());
                return $archivo->getRealPath();
            }
        }

        $subdirectorios = File::directories($directorio);
        foreach ($subdirectorios as $subdirectorio) {
            $rutaArchivo = $this->buscarArchivoRecursivamente($subdirectorio, $nombreArchivo);
            if ($rutaArchivo) {
                return $rutaArchivo;
            }
        }

        return false;
    }

    // Método añadido para mostrar expedientes finalizados
    public function expedientesFinalizados()
    {
        $solicitudes = Formulario::whereNotNull('comprobante')->paginate(10);
        return view('control_user.ExpedientesSS', compact('solicitudes'));
    }
}
