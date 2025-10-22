<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicacionController extends Controller
{
    /**
     * Crear nueva publicación
     */
    public function store(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);

        $request->validate([
            'contenido' => 'required|string|max:1000',
            'tipo' => 'required|in:texto,imagen,archivo,galeria',
            'archivos.*' => 'file|max:10240', // 10MB máximo por archivo
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'string'
        ]);

        $archivos = [];
        
        // Procesar archivos subidos
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('publicaciones/' . $perfil->id, 'public');
                $archivos[] = [
                    'nombre' => $archivo->getClientOriginalName(),
                    'path' => $path,
                    'tipo' => $archivo->getMimeType(),
                    'tamaño' => $archivo->getSize()
                ];
            }
        }

        $publicacion = $perfil->publicaciones()->create([
            'contenido' => $request->contenido,
            'tipo' => $request->tipo,
            'archivos' => $archivos,
            'etiquetas' => $request->etiquetas ?? []
        ]);

        // Verificar badges por primera publicación
        $this->verificarBadges($perfil);

        return redirect()->back()->with('success', 'Publicación creada exitosamente');
    }

    /**
     * Editar publicación
     */
    public function edit($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::where('perfil_id', $perfil->id)->findOrFail($id);
        
        return view('publicaciones.edit', compact('publicacion'));
    }

    /**
     * Actualizar publicación
     */
    public function update(Request $request, $id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::where('perfil_id', $perfil->id)->findOrFail($id);

        $request->validate([
            'contenido' => 'required|string|max:1000',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'string'
        ]);

        $publicacion->update([
            'contenido' => $request->contenido,
            'etiquetas' => $request->etiquetas ?? []
        ]);

        return redirect()->route('perfil.feed')->with('success', 'Publicación actualizada exitosamente');
    }

    /**
     * Eliminar publicación
     */
    public function destroy($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::where('perfil_id', $perfil->id)->findOrFail($id);

        // Eliminar archivos del storage
        if ($publicacion->archivos) {
            foreach ($publicacion->archivos as $archivo) {
                Storage::disk('public')->delete($archivo['path']);
            }
        }

        $publicacion->delete();

        return redirect()->back()->with('success', 'Publicación eliminada exitosamente');
    }

    /**
     * Mostrar publicación individual
     */
    public function show($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $miPerfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::with([
            'perfil.alumno',
            'reacciones.perfil.alumno',
            'comentarios.perfil.alumno'
        ])->findOrFail($id);

        // Verificar si puede ver esta publicación
        if (!$miPerfil->puedeVerPerfil($publicacion->perfil_id)) {
            abort(403, 'No tienes permiso para ver esta publicación');
        }

        $miReaccion = $publicacion->reaccionDePerfil($miPerfil->id);
        $contadorReacciones = $publicacion->contarReaccionesPorTipo();

        return view('publicaciones.show', compact('publicacion', 'miReaccion', 'contadorReacciones'));
    }

    /**
     * Activar/desactivar publicación
     */
    public function toggleActive($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::where('perfil_id', $perfil->id)->findOrFail($id);
        
        $publicacion->update([
            'activa' => !$publicacion->activa
        ]);

        $estado = $publicacion->activa ? 'activada' : 'desactivada';
        
        return redirect()->back()->with('success', "Publicación {$estado} exitosamente");
    }

    /**
     * Buscar publicaciones
     */
    public function buscar(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $query = $request->input('q');
        $tipo = $request->input('tipo');
        $etiqueta = $request->input('etiqueta');

        $publicaciones = $perfil->feedPublicaciones();

        if ($query) {
            $publicaciones->where('contenido', 'LIKE', "%{$query}%");
        }

        if ($tipo) {
            $publicaciones->where('tipo', $tipo);
        }

        if ($etiqueta) {
            $publicaciones->whereJsonContains('etiquetas', $etiqueta);
        }

        $resultados = $publicaciones->with(['perfil.alumno', 'reacciones', 'comentarios'])
                                   ->paginate(10);

        return view('publicaciones.buscar', compact('resultados', 'query', 'tipo', 'etiqueta'));
    }

    /**
     * Obtener publicaciones por etiqueta
     */
    public function porEtiqueta($etiqueta)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicaciones = $perfil->feedPublicaciones()
                               ->whereJsonContains('etiquetas', $etiqueta)
                               ->with(['perfil.alumno', 'reacciones', 'comentarios'])
                               ->paginate(10);

        return view('publicaciones.etiqueta', compact('publicaciones', 'etiqueta'));
    }

    /**
     * Descargar archivo de publicación
     */
    public function descargarArchivo($publicacionId, $archivoIndex)
    {
        $alumno = Auth::guard('alumno')->user();
        $miPerfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::findOrFail($publicacionId);

        // Verificar permisos
        if (!$miPerfil->puedeVerPerfil($publicacion->perfil_id)) {
            abort(403, 'No tienes permiso para descargar este archivo');
        }

        if (!isset($publicacion->archivos[$archivoIndex])) {
            abort(404, 'Archivo no encontrado');
        }

        $archivo = $publicacion->archivos[$archivoIndex];
        
        if (!Storage::disk('public')->exists($archivo['path'])) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($archivo['path'], $archivo['nombre']);
    }

    /**
     * Obtener perfil del alumno
     */
    private function obtenerPerfil($alumno)
    {
        return $alumno->perfil ?? Perfil::create([
            'alumno_id' => $alumno->id,
            'configuracion_privacidad' => Perfil::configuracionPrivacidadDefecto()
        ]);
    }

    /**
     * Verificar y asignar badges automáticamente
     */
    private function verificarBadges($perfil)
    {
        $badges = \App\Models\Badge::where('activo', true)->get();
        
        foreach ($badges as $badge) {
            if ($badge->cumpleCriterios($perfil)) {
                $badge->asignarAPerfil($perfil->id);
            }
        }
    }
}
