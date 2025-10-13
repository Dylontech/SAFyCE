<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Alumno;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de la aplicación.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranca cualquier servicio de la aplicación.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Conceder implícitamente todos los permisos al rol "Super Admin"
        // Esto funciona en la aplicación utilizando funciones relacionadas con la puerta (gate) como auth()->user->can() y @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Definir la lógica de autorización para ver el menú de administrador
        Gate::define('view-admin-menu', function ($user) {
            // Verificar el rol del usuario si es instancia de User
            if ($user instanceof User) {
                return $user->hasRole('admin');
            }

            // Verificar el rol del alumno si es instancia de Alumno
            if ($user instanceof Alumno) {
                return $user->hasRole('admin');
            }

            return false;
        });

        // Compartir la configuración de WhatsApp en todas las vistas
       // $settings = DB::table('whatsapp_settings')->first();
       // View::share('whatsappSettings', $settings);
    }
}
