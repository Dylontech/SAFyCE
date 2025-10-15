<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Título
    |--------------------------------------------------------------------------
    | Aquí puedes cambiar el título predeterminado de tu panel de administración.
    |
    */

    'title' => 'Datos',
    'title_prefix' => '',
    'title_postfix' => '',
    'bottom_title' => 'Tablar',
    'current_version' => 'v4.8',

    /*
    |--------------------------------------------------------------------------
    | Logo del Panel de Administración
    |--------------------------------------------------------------------------
    |
    | Aquí puedes cambiar el logo de tu panel de administración.
    |
    */

    'logo' => '<b>Tab</b>LAR',
    'logo_img_alt' => 'Logo de Administración',

    /*
    |--------------------------------------------------------------------------
    | Logo de Autenticación
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar un logo alternativo para usar en tus pantallas de login y registro.
    | Cuando está deshabilitado, se usará el logo del panel de administración.
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'assets/tablar-logo.png',
            'alt' => 'Logo de Autenticación',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
     *
     * La ruta predeterminada es 'resources/views/vendor/tablar' como null. Establece tu ruta personalizada aquí si es necesario.
     */

    'views_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Diseño
    |--------------------------------------------------------------------------
    | Aquí cambiamos el diseño de tu panel de administración.
    |
    | Para instrucciones detalladas puedes mirar la sección de diseño aquí:
    |
    */

    'layout' => 'combo',
    //boxed, combo, condensed, fluid, fluid-vertical, horizontal, navbar-overlap, navbar-sticky, rtl, vertical, vertical-right, vertical-transparent

    'layout_light_sidebar' => null,
    'layout_light_topbar' => true,
    'layout_enable_top_header' => false,

    /*
    |--------------------------------------------------------------------------
    | Barra de Navegación Superior Fija
    |--------------------------------------------------------------------------
    |
    | Aquí puedes habilitar/deshabilitar la funcionalidad fija de la Barra de Navegación Superior.
    |
    | Para instrucciones detalladas, puedes mirar las clases de la Barra de Navegación Superior aquí:
    |
    */

    'sticky_top_nav_bar' => false,

    /*
    |--------------------------------------------------------------------------
    | Clases del Panel de Administración
    |--------------------------------------------------------------------------
    |
    | Aquí puedes cambiar la apariencia y comportamiento del panel de administración.
    |
    | Para instrucciones detalladas, puedes mirar las clases del panel de administración aquí:
    |
    */

    'classes_body' => '',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Aquí podemos modificar la configuración de URLs del panel de administración.
    |
    | Para instrucciones detalladas, puedes mirar la sección de URLs aquí:
    |
    */

    'use_route_url' => true,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password.request',
    'password_email_url' => 'password.email',
    'profile_url' => false,
    'setting_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Mostrar Alerta
    |--------------------------------------------------------------------------
    |
    | Visibilidad de Mostrar Alerta.
    |
    */
    'display_alert' => false,

    /*
    |--------------------------------------------------------------------------
    | Elementos del Menú
    |--------------------------------------------------------------------------
    |
    | Aquí podemos modificar la barra lateral/navegación superior del panel de administración.
    |
    | Para instrucciones detalladas puedes mirar aquí:
    |
    */

    'menu' => [
        // INICIOS POR ROL
        [
            'text' => 'Inicio',
            'icon' => 'ti ti-home',
            'url' => 'home',
            'roles' => ['admin', 'control_escolar', 'servicio_financiero', 'tester']
        ],
        [
            'text' => 'Inicio Alumno',
            'url' => 'alumnos_user',
            'icon' => 'ti ti-home',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Inicio Maestro',
            'url' => 'dashboard',
            'icon' => 'ti ti-home',
            'roles' => ['maestro']
        ],

        // SECCIÓN: ACCESOS EXCLUSIVOS TESTER
        [
            'header' => '🧪 ACCESOS TESTER',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Control Escolar - Alumnos',
            'url' => 'alumnos',
            'icon' => 'ti ti-users',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Control Escolar - Solicitudes Exámenes',
            'url' => 'control_user',
            'icon' => 'ti ti-file-text',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Control Escolar - Solicitudes Servicios',
            'url' => 'gestions',
            'icon' => 'ti ti-file-text',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Control Escolar - Materias',
            'url' => 'materias',
            'icon' => 'ti ti-book',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Control Escolar - Grupos',
            'url' => 'grupos',
            'icon' => 'ti ti-users-group',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Finanzas - Solicitudes Exámenes',
            'url' => 'solicitudes-servicios-s',
            'icon' => 'ti ti-file-text',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Finanzas - Solicitudes Servicios',
            'url' => 'finanzas',
            'icon' => 'ti ti-currency-dollar',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 General - Novedades',
            'url' => 'carrusel',
            'icon' => 'ti ti-photo',
            'roles' => ['tester']
        ],
        [
            'text' => '🧪 Especialidades',
            'url' => 'especialidades',
            'icon' => 'ti ti-tag',
            'roles' => ['tester']
        ],

        // SECCIÓN: ALUMNO (SOLO ALUMNOS)
        [
            'header' => 'FUNCIONALIDADES ALUMNO',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Nueva solicitud de pago de exámenes',
            'url' => 'formulario',
            'icon' => 'ti ti-file-plus',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Nueva solicitud de servicios',
            'url' => 'servicios',
            'icon' => 'ti ti-file-plus',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Ver Solicitudes de Exámenes',
            'url' => 'solicitudesE',
            'icon' => 'ti ti-list',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Ver Solicitudes de Servicios',
            'url' => 'formularios',
            'icon' => 'ti ti-list',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Mis Tareas',
            'url' => 'estudiantes/tareas',
            'icon' => 'ti ti-checklist',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Mis Horarios',
            'url' => 'estudiantes/horarios',
            'icon' => 'ti ti-calendar',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Mi Kardex Académico',
            'url' => 'kardex',
            'icon' => 'ti ti-school',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Salas Disponibles',
            'url' => 'estudiantes/salas',
            'icon' => 'ti ti-building',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Reuniones Virtuales',
            'url' => 'alumnos/reuniones',
            'icon' => 'ti ti-video',
            'roles' => ['alumno']
        ],

        // SECCIÓN: CONTROL ESCOLAR (SIN TESTER)
        [
            'header' => 'CONTROL ESCOLAR',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Alumnos',
            'url' => 'alumnos',
            'icon' => 'ti ti-users',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Solicitudes de Exámenes',
            'url' => 'control_user',
            'icon' => 'ti ti-file-text',
            'roles' => ['control_escolar']
        ],
        [
            'text' => 'Solicitudes de Servicios',
            'url' => 'gestions',
            'icon' => 'ti ti-file-text',
            'roles' => ['control_escolar']
        ],
        [
            'text' => 'Materias',
            'url' => 'materias',
            'icon' => 'ti ti-book',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Grupos',
            'url' => 'grupos',
            'icon' => 'ti ti-users-group',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Especialidades',
            'url' => 'especialidades',
            'icon' => 'ti ti-tag',
            'roles' => ['control_escolar', 'admin', ]
        ],
        [
            'text' => 'Salas',
            'url' => 'salas',
            'icon' => 'ti ti-door',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Horarios',
            'url' => 'horarios',
            'icon' => 'ti ti-calendar-time',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Tareas',
            'url' => 'tareas',
            'icon' => 'ti ti-clipboard-list',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Calificaciones',
            'url' => 'calificaciones',
            'icon' => 'ti ti-certificate',
            'roles' => ['control_escolar', 'admin']
        ],

        // SECCIÓN: SERVICIO FINANCIERO (SIN TESTER)
        [
            'header' => 'SERVICIO FINANCIERO',
            'roles' => ['servicio_financiero']
        ],
        [
            'text' => 'Ver Solicitudes de Exámenes',
            'url' => 'solicitudes-servicios-s',
            'icon' => 'ti ti-file-text',
            'roles' => ['servicio_financiero']
        ],
        [
            'text' => 'Solicitudes de Servicios Financieros',
            'url' => 'finanzas',
            'icon' => 'ti ti-currency-dollar',
            'roles' => ['servicio_financiero']
        ],

        // SECCIÓN: GENERAL (SIN TESTER)
        [
            'header' => 'GENERAL',
            'roles' => ['control_escolar', 'admin', 'servicio_financiero']
        ],
        [
            'text' => 'Novedades',
            'url' => 'carrusel',
            'icon' => 'ti ti-photo',
            'roles' => ['control_escolar', 'admin', 'servicio_financiero']
        ],
        // SECCION MAESTROS
        [
            'header' => 'MAESTROS',
            'roles' => ['maestro']
        ],
        [
            'text' => 'Mis Horarios',
            'url' => 'horarios',
            'icon' => 'ti ti-calendar-time',
            'roles' => ['maestro']
        ],
        [
            'text' => 'Mis Tareas',
            'url' => 'maestros/tareas',
            'icon' => 'ti ti-clipboard-list',
            'roles' => ['maestro']
        ],
        [
            'text' => 'Mis Calificaciones',
            'url' => 'maestros/calificaciones',
            'icon' => 'ti ti-certificate',
            'roles' => ['maestro']
        ],
        [
            'text' => 'salas',
            'url' => 'salas',
            'icon' => 'ti ti-door',
            'roles' => ['maestro']
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Filtros del Menú
    |--------------------------------------------------------------------------
    |
    | Aquí podemos modificar los filtros del menú del panel de administración.
    |
    | Para instrucciones detalladas puedes mirar la sección de filtros del menú aquí:
    |
    */
    'filters' => [
        TakiElias\Tablar\Menu\Filters\GateFilter::class,
        TakiElias\Tablar\Menu\Filters\HrefFilter::class,
        TakiElias\Tablar\Menu\Filters\SearchFilter::class,
        TakiElias\Tablar\Menu\Filters\ActiveFilter::class,
        TakiElias\Tablar\Menu\Filters\ClassesFilter::class,
        TakiElias\Tablar\Menu\Filters\LangFilter::class,
        TakiElias\Tablar\Menu\Filters\DataFilter::class,
        App\Filter\RolePermissionMenuFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Vite
    |--------------------------------------------------------------------------
    |
    | Aquí podemos habilitar el soporte de Vite.
    |
    | Para instrucciones detalladas puedes mirar Vite aquí:
    | https://laravel-vite.dev
    |
    */

    'vite' => true,

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Aquí podemos habilitar el soporte de Livewire.
    |
    | Para instrucciones detalladas puedes mirar livewire aquí:
    | https://livewire.laravel.com
    |
    */

    'livewire' => false,
];