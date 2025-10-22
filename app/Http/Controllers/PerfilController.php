<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Alumno;
use App\Models\RedSocial;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    /**
     * Mostrar el perfil del alumno autenticado
     */
    public function index()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);
        
        $estadisticas = $this->obtenerEstadisticas($perfil);
        $iconosDisponibles = $this->obtenerIconosDisponibles();
        
        return view('perfil.index', compact('perfil', 'estadisticas', 'iconosDisponibles'));
    }

    /**
     * Mostrar perfil de otro alumno
     */
    public function show($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $miPerfil = $this->obtenerOCrearPerfil($alumno);
        
        $perfil = Perfil::with(['alumno', 'redesSociales', 'badges'])->findOrFail($id);
        
        // Verificar si puede ver este perfil
        if (!$miPerfil->puedeVerPerfil($perfil->id)) {
            abort(403, 'No tienes permiso para ver este perfil');
        }
        
        $estadisticas = $this->obtenerEstadisticas($perfil);
        $publicacionesRecientes = $perfil->publicaciones()
                                        ->activas()
                                        ->latest()
                                        ->limit(5)
                                        ->get();
        
        return view('perfil.show', compact('perfil', 'estadisticas', 'publicacionesRecientes'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);
        
        $iconosDisponibles = $this->obtenerIconosDisponibles();
        $materiasDisponibles = $this->obtenerMateriasDisponibles();
        $redesSociales = $perfil->redesSociales()->get()->keyBy('plataforma');
        
        return view('perfil.edit', compact('perfil', 'iconosDisponibles', 'materiasDisponibles', 'redesSociales'));
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);

        $request->validate([
            'icono_personalizado' => 'required|string',
            'biografia' => 'nullable|string|max:500',
            'estado' => 'nullable|string|max:100',
            'materias_favoritas' => 'nullable|array',
            'configuracion_privacidad.visibilidad_perfil' => 'required|in:publico,compañeros,privado',
            'configuracion_privacidad.visibilidad_publicaciones' => 'required|in:publico,compañeros,privado',
            'configuracion_privacidad.permitir_comentarios' => 'boolean',
            'configuracion_privacidad.notificaciones_activas' => 'boolean',
        ]);

        $perfil->update([
            'icono_personalizado' => $request->icono_personalizado,
            'biografia' => $request->biografia,
            'estado' => $request->estado,
            'materias_favoritas' => $request->materias_favoritas ?? [],
            'configuracion_privacidad' => [
                'visibilidad_perfil' => $request->input('configuracion_privacidad.visibilidad_perfil'),
                'visibilidad_publicaciones' => $request->input('configuracion_privacidad.visibilidad_publicaciones'),
                'permitir_comentarios' => $request->boolean('configuracion_privacidad.permitir_comentarios'),
                'notificaciones_activas' => $request->boolean('configuracion_privacidad.notificaciones_activas'),
            ]
        ]);

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Actualizar redes sociales
     */
    public function updateRedesSociales(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);

        $request->validate([
            'redes_sociales' => 'array',
            'redes_sociales.*.plataforma' => 'required|string',
            'redes_sociales.*.usuario' => 'nullable|string',
            'redes_sociales.*.url' => 'nullable|url',
            'redes_sociales.*.visible' => 'boolean',
        ]);

        // Eliminar redes sociales existentes
        $perfil->redesSociales()->delete();

        // Agregar nuevas redes sociales
        foreach ($request->input('redes_sociales', []) as $redData) {
            if (!empty($redData['usuario']) || !empty($redData['url'])) {
                $perfil->redesSociales()->create([
                    'plataforma' => $redData['plataforma'],
                    'usuario' => $redData['usuario'],
                    'url' => $redData['url'],
                    'visible' => $redData['visible'] ?? true,
                ]);
            }
        }

        return redirect()->route('perfil.edit')->with('success', 'Redes sociales actualizadas correctamente');
    }

    /**
     * Mostrar feed de publicaciones
     */
    public function feed()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);
        
        $publicaciones = $perfil->feedPublicaciones()
                               ->with(['perfil.alumno', 'reacciones', 'comentarios.perfil.alumno'])
                               ->paginate(10);
        
        return view('perfil.feed', compact('publicaciones', 'perfil'));
    }

    /**
     * Mostrar galería de archivos
     */
    public function galeria()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);
        
        $publicacionesConArchivos = $perfil->publicaciones()
                                          ->whereNotNull('archivos')
                                          ->where('activa', true)
                                          ->orderBy('created_at', 'desc')
                                          ->paginate(12);
        
        return view('perfil.galeria', compact('publicacionesConArchivos', 'perfil'));
    }

    /**
     * Mostrar configuración de privacidad
     */
    public function configuracion()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerOCrearPerfil($alumno);
        
        return view('perfil.configuracion', compact('perfil'));
    }

    /**
     * Buscar perfiles de otros alumnos
     */
    public function buscar(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        $query = $request->input('q');
        
        $perfiles = Perfil::with('alumno')
            ->whereHas('alumno', function($q) use ($query, $alumno) {
                $q->where('Nombre', 'LIKE', "%{$query}%")
                  ->where('Grupo', $alumno->Grupo)
                  ->where('especialidad', $alumno->especialidad);
            })
            ->paginate(20);
        
        return view('perfil.buscar', compact('perfiles', 'query'));
    }

    /**
     * Obtener o crear perfil para un alumno
     */
    private function obtenerOCrearPerfil($alumno)
    {
        $perfil = $alumno->perfil ?? Perfil::create([
            'alumno_id' => $alumno->id,
            'configuracion_privacidad' => Perfil::configuracionPrivacidadDefecto()
        ]);

        return $perfil;
    }

    /**
     * Obtener estadísticas del perfil
     */
    private function obtenerEstadisticas($perfil)
    {
        return [
            'total_publicaciones' => $perfil->publicaciones()->count(),
            'total_reacciones_recibidas' => $perfil->publicaciones()->sum('total_reacciones'),
            'total_comentarios_recibidos' => $perfil->publicaciones()->sum('total_comentarios'),
            'badges_obtenidos' => $perfil->badges()->count(),
            'puntos_actividad' => $perfil->puntos_actividad
        ];
    }

    /**
     * Obtener iconos disponibles
     */
    private function obtenerIconosDisponibles()
    {
        return [
            'student-default' => 'Estudiante por defecto',
            'student-books' => 'Estudiante con libros',
            'student-laptop' => 'Estudiante con laptop',
            'student-science' => 'Estudiante de ciencias',
            'student-art' => 'Estudiante de arte',
            'student-music' => 'Estudiante de música',
            'student-sports' => 'Estudiante deportista',
            'student-tech' => 'Estudiante de tecnología',
            'student-creative' => 'Estudiante creativo',
            'student-leader' => 'Estudiante líder'
        ];
    }

    /**
     * Obtener materias disponibles
     */
    private function obtenerMateriasDisponibles()
    {
        // Esto debería obtener las materias de la especialidad del alumno
        return [
            'matematicas' => 'Matemáticas',
            'fisica' => 'Física',
            'quimica' => 'Química',
            'ingles' => 'Inglés',
            'programacion' => 'Programación',
            'electronica' => 'Electrónica',
            'mecanica' => 'Mecánica',
            'administracion' => 'Administración'
        ];
    }
}
