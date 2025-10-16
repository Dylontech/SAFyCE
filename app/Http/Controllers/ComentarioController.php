<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Publicacion;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    /**
     * Crear nuevo comentario
     */
    public function store(Request $request, $publicacionId)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::findOrFail($publicacionId);
        
        // Verificar permisos
        if (!$perfil->puedeVerPerfil($publicacion->perfil_id)) {
            return response()->json(['error' => 'No tienes permiso para comentar en esta publicación'], 403);
        }

        // Verificar si la publicación permite comentarios
        $configuracionAutor = $publicacion->perfil->configuracion_privacidad;
        if (!($configuracionAutor['permitir_comentarios'] ?? true)) {
            return response()->json(['error' => 'Esta publicación no permite comentarios'], 403);
        }

        $request->validate([
            'contenido' => 'required|string|max:500'
        ]);

        $comentario = Comentario::create([
            'publicacion_id' => $publicacionId,
            'perfil_id' => $perfil->id,
            'contenido' => $request->contenido
        ]);

        $comentario->load('perfil.alumno');

        return response()->json([
            'success' => true,
            'comentario' => $comentario,
            'total_comentarios' => $publicacion->refresh()->total_comentarios
        ]);
    }

    /**
     * Editar comentario
     */
    public function update(Request $request, $id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $comentario = Comentario::findOrFail($id);

        // Verificar si puede editar el comentario
        if (!$comentario->puedeSerEditado($perfil->id)) {
            return response()->json(['error' => 'No puedes editar este comentario o el tiempo límite ha expirado'], 403);
        }

        $request->validate([
            'contenido' => 'required|string|max:500'
        ]);

        $comentario->update([
            'contenido' => $request->contenido
        ]);

        $comentario->load('perfil.alumno');

        return response()->json([
            'success' => true,
            'comentario' => $comentario
        ]);
    }

    /**
     * Eliminar comentario
     */
    public function destroy($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $comentario = Comentario::findOrFail($id);

        // Verificar si puede eliminar el comentario
        if (!$comentario->puedeSerEliminado($perfil->id)) {
            return response()->json(['error' => 'No tienes permiso para eliminar este comentario'], 403);
        }

        $publicacionId = $comentario->publicacion_id;
        $comentario->delete();

        $publicacion = Publicacion::find($publicacionId);

        return response()->json([
            'success' => true,
            'total_comentarios' => $publicacion->refresh()->total_comentarios
        ]);
    }

    /**
     * Obtener comentarios de una publicación
     */
    public function index($publicacionId)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::findOrFail($publicacionId);
        
        // Verificar permisos
        if (!$perfil->puedeVerPerfil($publicacion->perfil_id)) {
            return response()->json(['error' => 'No tienes permiso para ver estos comentarios'], 403);
        }

        $comentarios = $publicacion->comentarios()
                                  ->activos()
                                  ->with('perfil.alumno')
                                  ->orderBy('created_at', 'asc')
                                  ->get();

        return response()->json([
            'comentarios' => $comentarios,
            'total_comentarios' => $comentarios->count(),
            'puede_comentar' => $publicacion->perfil->configuracion_privacidad['permitir_comentarios'] ?? true
        ]);
    }

    /**
     * Activar/desactivar comentario
     */
    public function toggleActive($id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $comentario = Comentario::findOrFail($id);

        // Solo el autor del comentario o de la publicación puede activar/desactivar
        if ($comentario->perfil_id !== $perfil->id && 
            $comentario->publicacion->perfil_id !== $perfil->id) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
        }

        $comentario->update([
            'activo' => !$comentario->activo
        ]);

        return response()->json([
            'success' => true,
            'activo' => $comentario->activo,
            'comentario' => $comentario->load('perfil.alumno')
        ]);
    }

    /**
     * Buscar comentarios del usuario
     */
    public function misComentarios(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $query = $request->input('q');
        
        $comentarios = Comentario::where('perfil_id', $perfil->id)
                                ->with(['publicacion.perfil.alumno'])
                                ->when($query, function($q) use ($query) {
                                    return $q->where('contenido', 'LIKE', "%{$query}%");
                                })
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);

        return response()->json([
            'comentarios' => $comentarios,
            'query' => $query
        ]);
    }

    /**
     * Obtener estadísticas de comentarios
     */
    public function estadisticas()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);

        $comentariosRealizados = Comentario::where('perfil_id', $perfil->id)->count();
        
        $comentariosRecibidos = Comentario::whereIn('publicacion_id', 
                                          $perfil->publicaciones()->pluck('id'))
                                         ->count();

        $comentariosRecientes = Comentario::where('perfil_id', $perfil->id)
                                         ->where('created_at', '>=', now()->subDays(7))
                                         ->count();

        $publicacionesMasComentadas = $perfil->publicaciones()
                                           ->withCount('comentarios')
                                           ->orderBy('comentarios_count', 'desc')
                                           ->limit(5)
                                           ->get();

        return response()->json([
            'comentarios_realizados' => $comentariosRealizados,
            'comentarios_recibidos' => $comentariosRecibidos,
            'comentarios_esta_semana' => $comentariosRecientes,
            'publicaciones_mas_comentadas' => $publicacionesMasComentadas
        ]);
    }

    /**
     * Reportar comentario
     */
    public function reportar(Request $request, $id)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $comentario = Comentario::findOrFail($id);

        $request->validate([
            'motivo' => 'required|string|in:spam,acoso,contenido_inapropiado,otro',
            'descripcion' => 'nullable|string|max:200'
        ]);

        // Aquí podrías implementar un sistema de reportes
        // Por ahora solo retornamos éxito
        
        return response()->json([
            'success' => true,
            'message' => 'Comentario reportado exitosamente'
        ]);
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
}
