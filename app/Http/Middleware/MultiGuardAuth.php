<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MultiGuardAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado en cualquiera de los guards
        if (Auth::guard('web')->check() || Auth::guard('alumno')->check()) {
            return $next($request);
        }

        // Si no está autenticado en ningún guard, redirigir al login
        return redirect()->route('login');
    }
}
