<?php

namespace App\Http\Controllers;

use App\Models\Reaccion;
use App\Models\Publicacion;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReaccionController extends Controller
{
    /**
     * Crear o actualizar reacción
     */
    public function toggle(Request $request, $publicacionId)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::findOrFail($publicacionId);
        
        // Verificar si puede ver la publicación
        if (!$perfil->puedeVerPerfil($publicacion->perfil_id)) {
            return response()->json(['error' => 'No tienes permiso para reaccionar a esta publicación'], 403);
        }

        $request->validate([
            'tipo' => 'required|in:like,love,wow,funny,sad,angry'
        ]);

        $reaccionExistente = Reaccion::where('publicacion_id', $publicacionId)
                                   ->where('perfil_id', $perfil->id)
                                   ->first();

        if ($reaccionExistente) {
            if ($reaccionExistente->tipo === $request->tipo) {
                // Si es la misma reacción, eliminarla
                $reaccionExistente->delete();
                $accion = 'eliminada';
            } else {
                // Si es diferente, actualizarla
                $reaccionExistente->update(['tipo' => $request->tipo]);
                $accion = 'actualizada';
            }
        } else {
            // Crear nueva reacción
            Reaccion::create([
                'publicacion_id' => $publicacionId,
                'perfil_id' => $perfil->id,
                'tipo' => $request->tipo
            ]);
            $accion = 'creada';
        }

        // Obtener contadores actualizados
        $publicacion->refresh();
        $contadorReacciones = $publicacion->contarReaccionesPorTipo();
        $miReaccion = $publicacion->reaccionDePerfil($perfil->id);

        return response()->json([
            'success' => true,
            'accion' => $accion,
            'total_reacciones' => $publicacion->total_reacciones,
            'contador_por_tipo' => $contadorReacciones,
            'mi_reaccion' => $miReaccion ? $miReaccion->tipo : null
        ]);
    }

    /**
     * Eliminar reacción
     */
    public function destroy($publicacionId)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $reaccion = Reaccion::where('publicacion_id', $publicacionId)
                           ->where('perfil_id', $perfil->id)
                           ->first();

        if (!$reaccion) {
            return response()->json(['error' => 'No tienes reacción en esta publicación'], 404);
        }

        $reaccion->delete();

        $publicacion = Publicacion::find($publicacionId);
        $publicacion->refresh();

        return response()->json([
            'success' => true,
            'total_reacciones' => $publicacion->total_reacciones,
            'contador_por_tipo' => $publicacion->contarReaccionesPorTipo()
        ]);
    }

    /**
     * Obtener reacciones de una publicación
     */
    public function show($publicacionId)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::findOrFail($publicacionId);
        
        // Verificar permisos
        if (!$perfil->puedeVerPerfil($publicacion->perfil_id)) {
            return response()->json(['error' => 'No tienes permiso para ver estas reacciones'], 403);
        }

        $reacciones = $publicacion->reacciones()
                                 ->with('perfil.alumno')
                                 ->get()
                                 ->groupBy('tipo');

        $contadorPorTipo = $publicacion->contarReaccionesPorTipo();
        $miReaccion = $publicacion->reaccionDePerfil($perfil->id);

        return response()->json([
            'reacciones_agrupadas' => $reacciones,
            'contador_por_tipo' => $contadorPorTipo,
            'total_reacciones' => $publicacion->total_reacciones,
            'mi_reaccion' => $miReaccion ? $miReaccion->tipo : null,
            'tipos_disponibles' => Reaccion::TIPOS
        ]);
    }

    /**
     * Obtener usuarios que reaccionaron con un tipo específico
     */
    public function porTipo($publicacionId, $tipo)
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);
        
        $publicacion = Publicacion::findOrFail($publicacionId);
        
        // Verificar permisos
        if (!$perfil->puedeVerPerfil($publicacion->perfil_id)) {
            return response()->json(['error' => 'No tienes permiso para ver estas reacciones'], 403);
        }

        if (!array_key_exists($tipo, Reaccion::TIPOS)) {
            return response()->json(['error' => 'Tipo de reacción no válido'], 400);
        }

        $reacciones = $publicacion->reacciones()
                                 ->where('tipo', $tipo)
                                 ->with('perfil.alumno')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return response()->json([
            'tipo' => $tipo,
            'emoji' => Reaccion::TIPOS[$tipo],
            'reacciones' => $reacciones,
            'total' => $reacciones->count()
        ]);
    }

    /**
     * Obtener estadísticas de reacciones del perfil
     */
    public function estadisticas()
    {
        $alumno = Auth::guard('alumno')->user();
        $perfil = $this->obtenerPerfil($alumno);

        $reaccionesDadas = Reaccion::where('perfil_id', $perfil->id)
                                  ->selectRaw('tipo, COUNT(*) as total')
                                  ->groupBy('tipo')
                                  ->get()
                                  ->pluck('total', 'tipo');

        $reaccionesRecibidas = Reaccion::whereIn('publicacion_id', 
                                       $perfil->publicaciones()->pluck('id'))
                                     ->selectRaw('tipo, COUNT(*) as total')
                                     ->groupBy('tipo')
                                     ->get()
                                     ->pluck('total', 'tipo');

        return response()->json([
            'reacciones_dadas' => $reaccionesDadas,
            'reacciones_recibidas' => $reaccionesRecibidas,
            'total_dadas' => $reaccionesDadas->sum(),
            'total_recibidas' => $reaccionesRecibidas->sum(),
            'tipos_disponibles' => Reaccion::TIPOS
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
