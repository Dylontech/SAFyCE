<?php

namespace App\Http\Controllers;

use App\Models\PaginaInicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminPaginaInicioController extends Controller
{
    // Configuración de imágenes
    private $imageConfig = [
        'formats_soportados' => ['jpeg', 'png', 'jpg', 'gif', 'webp', 'svg'],
        'tamaño_maximo' => 10240, // 10MB en KB
        'alta_definicion' => [
            'max_width' => 3840, // 4K
            'max_height' => 2160,
        ],
    ];

    public function __construct()
    {
        // Verificar que sea admin o tester usando método flexible
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                abort(403, 'No tienes permisos para acceder a esta página');
            }
            
            $user = Auth::user();
            $isAdminOrTester = $this->checkIfUserIsAdminOrTester($user);
            
            if (!$isAdminOrTester) {
                abort(403, 'No tienes permisos de administrador o tester');
            }
            
            return $next($request);
        });
    }

    /**
     * Método flexible para verificar si el usuario es admin o tester
     * Compatible con diferentes estructuras de base de datos
     */
    private function checkIfUserIsAdminOrTester($user)
    {
        // Verificar si el usuario tiene el rol de admin o tester
        return $user->hasRole(['admin', 'tester']);
    }

    public function edit()
    {
        $configuracion = PaginaInicio::first();
        
        if (!$configuracion) {
            $configuracion = new PaginaInicio();
        }

        // Asegurar que los campos JSON sean arrays
        $configuracion->miembros_equipo = $this->ensureArray($configuracion->miembros_equipo);
        $configuracion->novedades = $this->ensureArray($configuracion->novedades);
        $configuracion->about = $this->ensureArray($configuracion->about);

        return view('admin.pagina-inicio-edit', compact('configuracion'))
            ->with('imageConfig', $this->imageConfig);
    }

    /**
     * Convierte campos JSON a array de forma segura
     */
    private function ensureArray($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    public function update(Request $request)
    {
        $configuracion = PaginaInicio::firstOrNew([]);

        // Validación mejorada para imágenes
        $request->validate([
            'titulo_principal' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen_principal' => $this->getImageValidationRules(),
            'titulo_equipo' => 'required|string|max:255',
            'titulo_novedades' => 'required|string|max:255',
            'titulo_contacto' => 'required|string|max:255',
            'email_contacto' => 'required|email',
            'telefono_contacto' => 'required|string|max:20',
            'direccion_contacto' => 'required|string|max:255',
            'facebook' => 'nullable|url',
            'whatsapp' => 'nullable|url',
            'instagram' => 'nullable|url',
            'miembros_foto.*' => $this->getImageValidationRules(false),
        ]);

        $data = $request->only([
            'titulo_principal', 'descripcion', 'titulo_equipo', 
            'titulo_novedades', 'titulo_contacto', 'email_contacto',
            'telefono_contacto', 'direccion_contacto', 'facebook',
            'whatsapp', 'instagram'
        ]);

        // Procesar sección About
        $about = [
            'mision' => $request->input('about_mision', ''),
            'vision' => $request->input('about_vision', ''),
            'valores' => array_filter($request->input('about_valores', []), function($v) { 
                return $v !== null && $v !== ''; 
            })
        ];
        $data['about'] = json_encode($about);

        // Procesar imagen principal CON DATOS DE RECORTE
        if ($request->hasFile('imagen_principal')) {
            $data['imagen_principal'] = $this->procesarImagen(
                $request->file('imagen_principal'), 
                'pagina-inicio',
                $configuracion->imagen_principal
            );
            
            // Guardar datos de recorte de imagen principal
            if ($request->has('imagen_principal_crop_data') && !empty($request->imagen_principal_crop_data)) {
                $data['imagen_principal_crop_data'] = $request->imagen_principal_crop_data;
            }
        } elseif ($request->has('imagen_principal_actual') && !empty($request->imagen_principal_actual)) {
            $data['imagen_principal'] = $request->imagen_principal_actual;
            
            // Mantener datos de recorte existentes si no hay nueva imagen
            if ($request->has('imagen_principal_crop_data') && !empty($request->imagen_principal_crop_data)) {
                $data['imagen_principal_crop_data'] = $request->imagen_principal_crop_data;
            } elseif (empty($data['imagen_principal_crop_data'])) {
                $data['imagen_principal_crop_data'] = $configuracion->imagen_principal_crop_data;
            }
        }

        // Procesar miembros del equipo CON DATOS DE RECORTE
        $miembros = [];
        if ($request->has('miembros_nombre')) {
            foreach ($request->miembros_nombre as $index => $nombre) {
                if (!empty($nombre)) {
                    $miembro = [
                        'nombre' => $nombre,
                        'cargo' => $request->miembros_cargo[$index] ?? '',
                        'foto' => null,
                        'crop_data' => $request->miembros_crop_data[$index] ?? null
                    ];

                    // Procesar foto del miembro
                    if ($request->hasFile("miembros_foto") && isset($request->file("miembros_foto")[$index])) {
                        $foto = $request->file("miembros_foto")[$index];
                        if ($foto->isValid()) {
                            $fotoActual = null;
                            if (isset($request->miembros_foto_actual[$index])) {
                                $fotoActual = $request->miembros_foto_actual[$index];
                            }
                            
                            $miembro['foto'] = $this->procesarImagen(
                                $foto, 
                                'equipo',
                                $fotoActual
                            );
                        }
                    } elseif ($request->has("miembros_foto_actual") && isset($request->miembros_foto_actual[$index])) {
                        $miembro['foto'] = $request->miembros_foto_actual[$index];
                    }

                    // Si hay datos de recorte pero no nueva foto, mantener los datos de recorte existentes
                    if (empty($miembro['crop_data']) && !empty($miembro['foto'])) {
                        // Buscar datos de recorte existentes en la configuración actual
                        $miembroExistente = $this->buscarMiembroExistente($configuracion->miembros_equipo, $miembro['foto']);
                        if ($miembroExistente && isset($miembroExistente['crop_data'])) {
                            $miembro['crop_data'] = $miembroExistente['crop_data'];
                        }
                    }

                    $miembros[] = $miembro;
                }
            }
        }
        $data['miembros_equipo'] = json_encode($miembros);

        // Procesar novedades
        $novedades = [];
        if ($request->has('novedades_titulo')) {
            foreach ($request->novedades_titulo as $index => $titulo) {
                if (!empty($titulo)) {
                    $novedades[] = [
                        'titulo' => $titulo,
                        'fecha' => $request->novedades_fecha[$index] ?? now()->format('Y-m-d'),
                        'descripcion' => $request->novedades_descripcion[$index] ?? ''
                    ];
                }
            }
        }
        $data['novedades'] = json_encode($novedades);

        $configuracion->fill($data);
        $configuracion->save();

        return redirect()->route('admin.pagina-inicio.edit')
            ->with('success', 'Configuración actualizada correctamente');
    }

    /**
     * Busca un miembro existente por su foto para mantener datos de recorte
     */
    private function buscarMiembroExistente($miembrosEquipo, $foto)
    {
        if (empty($miembrosEquipo)) {
            return null;
        }

        $miembros = $this->ensureArray($miembrosEquipo);
        
        foreach ($miembros as $miembro) {
            if (isset($miembro['foto']) && $miembro['foto'] === $foto) {
                return $miembro;
            }
        }
        
        return null;
    }

    /**
     * Genera reglas de validación para imágenes
     */
    private function getImageValidationRules($required = false)
    {
        $rules = [
            'nullable',
            'image',
            'mimes:' . implode(',', $this->imageConfig['formats_soportados']),
            'max:' . $this->imageConfig['tamaño_maximo']
        ];

        if ($required) {
            $rules[0] = 'required';
        }

        return $rules;
    }

    /**
     * Procesa imágenes sin dependencias externas
     */
    private function procesarImagen($archivo, $directorio, $imagenAnterior = null)
    {
        // Eliminar imagen anterior si existe
        if ($imagenAnterior && Storage::exists('public/' . $imagenAnterior)) {
            Storage::delete('public/' . $imagenAnterior);
        }

        // Validar dimensiones si es una imagen (no SVG)
        if ($archivo->getClientOriginalExtension() !== 'svg') {
            $this->validarDimensionesImagen($archivo);
        }

        // Guardar imagen con nombre único
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = uniqid() . '.' . $extension;
        $path = $archivo->storeAs($directorio, $nombreArchivo, 'public');

        return $path;
    }

    /**
     * Valida las dimensiones de la imagen
     */
    private function validarDimensionesImagen($archivo)
    {
        $dimensiones = getimagesize($archivo->getPathname());
        $ancho = $dimensiones[0];
        $alto = $dimensiones[1];

        // Solo valida sin loggear
        if ($ancho > $this->imageConfig['alta_definicion']['max_width'] || 
            $alto > $this->imageConfig['alta_definicion']['max_height']) {
            // La imagen es grande, pero permitimos la subida
            return true;
        }

        return true;
    }

    /**
     * Formatea bytes a formato legible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Método para obtener la configuración de imágenes
     */
    public function getImageConfig()
    {
        return $this->imageConfig;
    }
}