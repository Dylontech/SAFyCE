<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formulario;
use App\Models\FormularioE; // Agregar este modelo
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class FormularioController extends Controller
{
    public function __construct()
    {
        // Aplicar middleware para verificar autenticación
        $this->middleware('auth:web,alumno');
    }

    /**
     * Verificar si el usuario tiene permisos (alumno, admin o tester)
     */
    private function checkUserPermissions()
    {
        // Si es alumno autenticado, permitir acceso
        if (Auth::guard('alumno')->check()) {
            return true;
        }
        
        // Si es usuario web, verificar si es admin o tester
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            // Verificar roles directamente sin usar hasRole
            if ($user && ($user->rol === 'admin' || $user->rol === 'tester')) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Verificar si el usuario es admin o tester
     */
    private function isAdminOrTester()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            return $user && ($user->rol === 'admin' || $user->rol === 'tester');
        }
        return false;
    }

    /**
     * Obtener el ID del alumno según el tipo de usuario
     */
    private function getAlumnoId()
    {
        if (Auth::guard('alumno')->check()) {
            return Auth::guard('alumno')->id();
        }
        
        // Para admin/tester, pueden necesitar un ID específico o null
        return null;
    }

    public function index(Request $request)
    {
        // Permitir acceso a alumnos, admin y tester
        if (!$this->checkUserPermissions()) {
            return redirect()->route('login')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $search = $request->input('search');
        $especialidad = $request->input('especialidad');
        $grupo = $request->input('grupo');
        $control = $request->input('control');
        
        $query = Formulario::query();
        
        // Si es alumno, solo ver sus solicitudes
        if (Auth::guard('alumno')->check()) {
            $query->where('alumno_id', $this->getAlumnoId());
        }
        
        // Búsquedas (para admin/tester pueden buscar en todos los registros)
        if ($request->filled('search')) {
            $query->where('Nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_control', 'like', '%' . $request->search . '%')
                  ->orWhere('CURP', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('tipo_servicio')) {
            $query->where('tipo_servicio', $request->tipo_servicio);
        }
        if ($request->filled('fecha_solicitud')) {
            $query->whereDate('fecha', $request->fecha_solicitud);
        }
    
        $formularios = $query->paginate(10);
    
        $tipos_servicio = Formulario::select('tipo_servicio')->distinct()->get();
        $fecha_solicitud = Formulario::select('fecha')->distinct()->get();
        $especialidades = Formulario::distinct()->pluck('especialidad');
        $grupos = Formulario::distinct()->pluck('grupo');
        $controles = Formulario::distinct()->pluck('control');
    
        return view('alumnos_user.SolicitudesS', compact('formularios', 'especialidades', 'grupos', 'controles'))
            ->with('i', (request()->input('page', 1) - 1) * $formularios->perPage());
    }

    public function expediente()
    {
        // Permitir acceso a alumnos, admin y tester
        if (!$this->checkUserPermissions()) {
            return redirect()->route('login')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $query = Formulario::query();
        
        // Si es alumno, solo ver sus expedientes completos
        if (Auth::guard('alumno')->check()) {
            $query->where('alumno_id', $this->getAlumnoId());
        }
        
        $formularios = $query->whereNotNull('comprobante')
            ->whereNotNull('liga_de_pago')
            ->whereNotNull('comprobante_alumno')
            ->paginate(10);

        return view('alumnos_user.ExpedienteSS', compact('formularios'));
    }

    public function store(Request $request)
    {
        // Solo alumnos pueden crear solicitudes
        if (!Auth::guard('alumno')->check()) {
            return redirect()->route('login')->with('error', 'Solo los alumnos pueden crear solicitudes.');
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'control' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'curp' => 'required|string|max:18',
            'tipo_servicio' => 'nullable|string',
            'status' => 'nullable|string',
            'comentario' => 'nullable|string',
            'comentario_financiero' => 'nullable|string',
            'liga_de_pago' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:10240',
            'comprobante_alumno' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:10240',
            'comprobante' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:10240',
            'comprobante_oficial' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:10240',
        ]);

        $data['alumno_id'] = $this->getAlumnoId();

        if ($request->hasFile('liga_de_pago')) {
            $data['liga_de_pago'] = $request->file('liga_de_pago')->store('pagos');
        }
        if ($request->hasFile('comprobante_alumno')) {
            $data['comprobante_alumno'] = $request->file('comprobante_alumno')->store('comprobantes');
        }
        if ($request->hasFile('comprobante')) {
            $data['comprobante'] = $request->file('comprobante')->store('comprobantes');
        }
        if ($request->hasFile('comprobante_oficial')) {
            $data['comprobante_oficial'] = $request->file('comprobante_oficial')->store('comprobantes');
        }

        try {
            Formulario::create($data);
            return redirect()->route('formularios.index')->with('success', 'Solicitud enviada con éxito');
        } catch (\Exception $e) {
            return redirect()->route('formularios.index')->with('error', 'Hubo un problema al enviar la solicitud');
        }
    }

    public function destroy($id)
    {
        // Permitir a alumnos eliminar sus propias solicitudes, y a admin/tester eliminar cualquier solicitud
        if (!$this->checkUserPermissions()) {
            return redirect()->route('login')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        try {
            $formulario = Formulario::findOrFail($id);
            
            // Si es alumno, solo puede eliminar sus propias solicitudes
            if (Auth::guard('alumno')->check() && $formulario->alumno_id !== $this->getAlumnoId()) {
                return redirect()->route('formularios.index')->with('error', 'No tienes permisos para eliminar esta solicitud.');
            }
            
            $formulario->delete();
            return redirect()->route('formularios.index')->with('success', 'Solicitud eliminada con éxito');
        } catch (\Exception $e) {
            return redirect()->route('formularios.index')->with('error', 'Hubo un problema al eliminar la solicitud');
        }
    }

    // Método para subir el comprobante del alumno
    public function subirComprobanteAlumno(Request $request, $id)
    {
        // Solo alumnos pueden subir sus propios comprobantes
        if (!Auth::guard('alumno')->check()) {
            return redirect()->route('login')->with('error', 'Solo los alumnos pueden subir comprobantes.');
        }

        $request->validate([
            'comprobante_alumno' => 'required|file|mimes:pdf,jpg,png|max:10240',
        ]);

        if ($request->hasFile('comprobante_alumno')) {
            $file = $request->file('comprobante_alumno');
            $path = $file->store('comprobantes', 'public');
            $fileName = basename($path);

            $formulario = Formulario::where('alumno_id', $this->getAlumnoId())->where('id', $id)->first();

            if ($formulario) {
                $formulario->comprobante_alumno = $path;
                $formulario->save();
                return redirect()->back()->with('success', 'Comprobante subido exitosamente.');
            } else {
                return redirect()->back()->with('error', 'No se encontró la solicitud especificada.');
            }
        }

        return redirect()->back()->with('error', 'Error al subir el comprobante.');
    }

    /**
     * Método CORREGIDO para descargar la liga de pago - USA FORMULARIO_E
     */
    public function downloadLigaDePago($id)
    {
        try {
            Log::info("=== INICIANDO DESCARGA LIGA DE PAGO DESDE FORMULARIO_E ===");
            Log::info("ID solicitado: {$id}");

            // Para alumnos: solo sus propias solicitudes
            if (Auth::guard('alumno')->check()) {
                $formulario = FormularioE::where('alumno_id', $this->getAlumnoId())->where('id', $id)->first();
                Log::info("Buscando formulario_e para alumno ID: " . $this->getAlumnoId());
            } else {
                // Para admin/tester: cualquier solicitud
                $formulario = FormularioE::find($id);
                Log::info("Buscando formulario_e para admin/tester");
            }

            if (!$formulario) {
                Log::error("Formulario_E no encontrado - ID: {$id}");
                return redirect()->back()->with('error', 'Solicitud no encontrada.');
            }

            Log::info("Formulario_E encontrado - ID: {$formulario->id}, Alumno ID: {$formulario->alumno_id}");

            if (empty($formulario->liga_de_pago)) {
                Log::error("Liga de pago vacía en formulario_E ID: {$id}");
                return redirect()->back()->with('error', 'No se encontró la liga de pago para esta solicitud.');
            }

            $rutaArchivo = $formulario->liga_de_pago;
            Log::info("Ruta del archivo en BD: '{$rutaArchivo}'");
            
            // Verificar si la ruta existe en storage
            if (Storage::exists($rutaArchivo)) {
                Log::info("Archivo encontrado en storage, procediendo con descarga");
                return Storage::download($rutaArchivo);
            } else {
                Log::error("Archivo NO encontrado en storage - Ruta: '{$rutaArchivo}'");
                return redirect()->back()->with('error', 
                    'El archivo de liga de pago no se encuentra en el servidor. ' .
                    'Ruta buscada: ' . $rutaArchivo
                );
            }

        } catch (\Exception $e) {
            Log::error("Error general en downloadLigaDePago: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la descarga: ' . $e->getMessage());
        }
    }

    // Métodos auxiliares
    private function convertSystemPathToStoragePath($systemPath)
    {
        $storagePath = str_replace(storage_path('app/'), '', $systemPath);
        $storagePath = str_replace(storage_path(), '', $storagePath);
        $storagePath = ltrim($storagePath, '/\\');
        return $storagePath;
    }

    private function getMimeTypeFromExtension($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'txt' => 'text/plain',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    public function downloadComprobante($id)
    {
        // Para alumnos: solo sus propias solicitudes
        // Para admin/tester: cualquier solicitud
        if (Auth::guard('alumno')->check()) {
            $formulario = Formulario::where('alumno_id', $this->getAlumnoId())->where('id', $id)->first();
        } else {
            $formulario = Formulario::find($id);
        }

        if ($formulario && Storage::exists($formulario->comprobante)) {
            return Storage::download($formulario->comprobante);
        } else {
            return redirect()->back()->with('error', 'No se encontró el comprobante o no tiene permisos para descargarlo.');
        }
    }

    public function downloadComprobanteAlumno($id)
    {
        // Para alumnos: solo sus propias solicitudes
        // Para admin/tester: cualquier solicitud
        if (Auth::guard('alumno')->check()) {
            $formulario = Formulario::where('alumno_id', $this->getAlumnoId())->where('id', $id)->first();
        } else {
            $formulario = Formulario::find($id);
        }

        if ($formulario && Storage::exists($formulario->comprobante_alumno)) {
            return Storage::download($formulario->comprobante_alumno);
        } else {
            return redirect()->back()->with('error', 'No se encontró el comprobante del alumno o no tiene permisos para descargarlo.');
        }
    }

    public function uploadComprobante(Request $request, $id)
    {
        // Solo admin/tester pueden subir comprobantes oficiales
        if (!$this->isAdminOrTester()) {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $request->validate([
            'comprobante' => 'required|file|mimes:pdf,jpg,png|max:10240',
            'comentario_financiero' => 'nullable|string',
            'comprobante_oficial' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            $path = $file->store('comprobantes', 'public');

            $formulario = Formulario::findOrFail($id);

            $formulario->comprobante = $path;
            $formulario->comentario_financiero = $request->input('comentario_financiero');

            if ($request->hasFile('comprobante_oficial')) {
                $fileOficial = $request->file('comprobante_oficial');
                $pathOficial = $fileOficial->store('comprobantes', 'public');
                $formulario->comprobante_oficial = $pathOficial;
            }

            $formulario->save();

            return redirect()->back()->with('success', 'Comprobante y comentario financiero subidos exitosamente.');
        }

        return redirect()->back()->with('error', 'Error al subir el comprobante.');
    }
    /**
 * NUEVO MÉTODO: Descargar liga de pago desde la tabla FORMULARIOS
 */
public function downloadLigaPagoFormularios($id)
{
    try {
        Log::info("=== INICIANDO DESCARGA LIGA DE PAGO DESDE FORMULARIOS ===");
        Log::info("ID solicitado: {$id}");

        // Para alumnos: solo sus propias solicitudes
        if (Auth::guard('alumno')->check()) {
            $formulario = Formulario::where('alumno_id', $this->getAlumnoId())->where('id', $id)->first();
            Log::info("Buscando en FORMULARIOS para alumno ID: " . $this->getAlumnoId());
        } else {
            // Para admin/tester: cualquier solicitud
            $formulario = Formulario::find($id);
            Log::info("Buscando en FORMULARIOS para admin/tester");
        }

        if (!$formulario) {
            Log::error("Formulario no encontrado en TABLA FORMULARIOS - ID: {$id}");
            return redirect()->back()->with('error', 'Solicitud no encontrada.');
        }

        Log::info("Formulario encontrado en FORMULARIOS - ID: {$formulario->id}");
        Log::info("Liga de pago en BD: '{$formulario->liga_de_pago}'");

        if (empty($formulario->liga_de_pago)) {
            Log::error("Liga de pago vacía en formulario ID: {$id}");
            return redirect()->back()->with('error', 'No se encontró la liga de pago para esta solicitud.');
        }

        $rutaArchivo = $formulario->liga_de_pago;
        Log::info("Ruta del archivo en BD: '{$rutaArchivo}'");
        
        // Verificar si la ruta existe en storage
        if (Storage::exists($rutaArchivo)) {
            Log::info("Archivo encontrado en storage, procediendo con descarga");
            return Storage::download($rutaArchivo);
        } else {
            // Intentar con diferentes ubicaciones posibles
            $possiblePaths = [
                $rutaArchivo,
                'public/' . $rutaArchivo,
                'app/public/' . $rutaArchivo,
                str_replace('public/', '', $rutaArchivo),
                'pagos/' . basename($rutaArchivo),
            ];
            
            foreach ($possiblePaths as $path) {
                if (Storage::exists($path)) {
                    Log::info("Archivo encontrado en ubicación alternativa: {$path}");
                    return Storage::download($path);
                }
            }
            
            Log::error("Archivo NO encontrado en storage - Ruta: '{$rutaArchivo}'");
            
            return redirect()->back()->with('error', 
                'El archivo de liga de pago no se encuentra en el servidor. ' .
                'Ruta buscada: ' . $rutaArchivo
            );
        }

    } catch (\Exception $e) {
        Log::error("Error en downloadLigaPagoFormularios: " . $e->getMessage());
        return redirect()->back()->with('error', 'Error al procesar la descarga: ' . $e->getMessage());
    }
}
}