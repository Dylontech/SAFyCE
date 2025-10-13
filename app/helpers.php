<?php

if (!function_exists('verificarAutenticacion')) {
    /**
     * Verifica si el usuario está autenticado.
     * Si no lo está, lo redirige a la página de login.
     */
    function verificarAutenticacion()
    {
        if (!auth()->check()) {
            // Si el usuario NO está logueado...
            abort(redirect('/login')->with('error', 'Debes iniciar sesión para acceder a esta página.'));
        }
        // Si sí está logueado, no hace nada y el código continúa.
    }
}