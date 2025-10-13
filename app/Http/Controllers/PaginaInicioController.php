<?php

namespace App\Http\Controllers;

use App\Models\PaginaInicio;
use Illuminate\Http\Request;

class PaginaInicioController extends Controller
{
    public function index()
    {
        $configuracion = PaginaInicio::first();
        
        if (!$configuracion) {
            // Si no existe configuración, crear una por defecto
            $configuracion = PaginaInicio::create([
                'titulo_principal' => 'Sistema de Gestión Escolar',
                'descripcion' => 'Plataforma integral para la administración de procesos académicos y administrativos',
                'titulo_equipo' => 'Nuestro Equipo de Desarrollo',
                'miembros_equipo' => json_encode([]),
                'titulo_novedades' => 'Últimas Novedades',
                'novedades' => json_encode([]),
                'titulo_contacto' => 'Contáctanos',
                'email_contacto' => 'contacto@example.com',
                'telefono_contacto' => '+1 234 567 8900',
                'direccion_contacto' => 'Dirección de ejemplo',
                'about' => 'example',
            ]);
        }

        return view('pagina-inicio', compact('configuracion'));
    }
}