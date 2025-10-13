@auth
    @php
        $user = Auth::user();
        $userName = 'Usuario';
        $userRoles = $user->roles->pluck('name')->implode(', ') ?? 'Sin rol';

        // Obtener el nombre según el rol
        if ($user->hasRole('alumno')) {
            $alumno = \App\Models\Alumno::find($user->id);
            $userName = $alumno ? $alumno->Nombre : 'Alumno';
        } else {
            $userName = $user->name ?? 'Usuario';
        }
    @endphp

    <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Abrir menú de usuario">
            <span class="avatar">{{ substr($userName, 0, 1) }}</span>
            <div class="d-none d-xl-block ps-2">
                <div>{{ $userRoles !== 'Sin rol' ? $userName : 'Sin rol' }}</div>
                <div class="mt-1 small text-muted">{{ $userRoles !== 'Sin rol' ? $userRoles : '' }}</div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            @php( $logout_url = View::getSection('logout_url') ?? config('tablar.logout_url', 'logout') )
            @php( $setting_url = View::getSection('setting_url') ?? config('tablar.setting_url', 'roles.index') )
            @php( $users_index_url = route('users.index') )

            @if (config('tablar.use_route_url', true))
                @php( $logout_url = $logout_url ? route($logout_url) : '' )
                @php( $setting_url = $setting_url ? route($setting_url) : route('roles.index') )
            @else
                @php( $logout_url = $logout_url ? url($logout_url) : '' )
                @php( $setting_url = $setting_url ? url($setting_url) : url('configuracion') )
            @endif

            {{-- ACCESO PARA ADMIN Y TESTER --}}
            @role('admin|tester')
                <a href="{{ $users_index_url }}" class="dropdown-item">Registro de Usuarios</a>
                <a href="{{ $setting_url }}" class="dropdown-item">Asignación de roles</a>
                
                <!-- NUEVA OPCIÓN: Configuración de Página de Inicio -->
                <a href="{{ route('admin.pagina-inicio.edit') }}" class="dropdown-item">
                    <i class="fas fa-home me-2"></i>Página de Inicio
                </a>
                
                <a href="{{ route('admin.index') }}" class="dropdown-item">Respaldo de base de datos</a>
                <a href="{{ route('edit.whatsapp.settings') }}" class="dropdown-item">Configuración de WhatsApp</a>
            @endrole

            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off text-red"></i> Salir
            </a>

            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('tablar.logout_method'))
                    {{ method_field(config('tablar.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </div>
    </div>
@else
    <div class="container text-center">
        <h1>Sin rol</h1>
    </div>
@endauth