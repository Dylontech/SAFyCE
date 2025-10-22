<?php

/*
 |--------------------------------------------------------------------------
 | Configuración organizada de Tablar
 |--------------------------------------------------------------------------
 | Se han introducido agrupaciones lógicas (brand, layout, urls, navigation,
 | integrations) para mejorar la mantenibilidad. Las claves planas originales
        ['text' => 'Alumnos', 'icon' => 'ti ti-users', 'route' => 'alumnos.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Solicitudes de Exámenes', 'icon' => 'ti ti-file-text', 'route' => 'control_user.index', 'roles' => ['control_escolar']],
        ['text' => 'Solicitudes de Servicios', 'icon' => 'ti ti-file-text', 'route' => 'gestions.index', 'roles' => ['control_escolar']],
        ['text' => 'Materias', 'icon' => 'ti ti-book', 'route' => 'materias.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Grupos', 'icon' => 'ti ti-users-group', 'route' => 'grupos.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Especialidades', 'icon' => 'ti ti-tag', 'route' => 'especialidades.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Salas', 'icon' => 'ti ti-door', 'route' => 'salas.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Horarios', 'icon' => 'ti ti-calendar-time', 'route' => 'horarios.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Tareas', 'icon' => 'ti ti-clipboard-list', 'route' => 'tareas.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Calificaciones', 'icon' => 'ti ti-certificate', 'route' => 'calificaciones.index', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Biblioteca Virtual', 'icon' => 'ti ti-book-2', 'route' => 'biblioteca-virtual', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Revisión de Documentos', 'icon' => 'ti ti-files', 'route' => 'control_documentos.index', 'roles' => ['control_escolar', 'controlescolar', 'admin']],

        // SERVICIO FINANCIERO
        ['header' => 'SERVICIO FINANCIERO', 'roles' => ['servicio_financiero']],
        ['text' => 'Ver Solicitudes de Exámenes', 'icon' => 'ti ti-file-text', 'route' => 'solicitudes-servicios-s.index', 'roles' => ['servicio_financiero']],
        ['text' => 'Solicitudes de Servicios Financieros', 'icon' => 'ti ti-currency-dollar', 'route' => 'finanzas.index', 'roles' => ['servicio_financiero']],

        // GENERAL
        ['header' => 'GENERAL', 'roles' => ['control_escolar', 'admin', 'servicio_financiero']],
        ['text' => 'Novedades', 'icon' => 'ti ti-photo', 'route' => 'carrusel.index', 'roles' => ['control_escolar', 'admin', 'servicio_financiero']],

        // MAESTROS
        ['header' => 'MAESTROS', 'roles' => ['maestro']],
        ['text' => 'Mis Horarios', 'icon' => 'ti ti-calendar-time', 'route' => 'maestros.horarios', 'roles' => ['maestro']],
        ['text' => 'Mis Tareas', 'icon' => 'ti ti-clipboard-list', 'route' => 'maestros.tareas.index', 'roles' => ['maestro']],
        ['text' => 'Mis Calificaciones', 'icon' => 'ti ti-certificate', 'route' => 'maestros.calificaciones.index', 'roles' => ['maestro']],
        ['text' => 'Salas', 'icon' => 'ti ti-door', 'route' => 'salas.index', 'roles' => ['maestro']],
        ['text' => 'Biblioteca Virtual', 'icon' => 'ti ti-book-2', 'route' => 'biblioteca-virtual', 'roles' => ['maestro']],
 | se mantienen para compatibilidad hacia atrás.
 */


$brand = [
    'title' => 'Datos',
    'title_prefix' => '',
    'title_postfix' => '',
    'bottom_title' => 'Tablar',
    'current_version' => 'v4.8',
    'logo' => '<b>Tab</b>LAR',
    'logo_img_alt' => 'Logo de Administración',
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
];

$layout = [
    'views_path' => null,
    'layout' => 'combo', // boxed, combo, condensed, fluid, fluid-vertical, horizontal, navbar-overlap, navbar-sticky, rtl, vertical, vertical-right, vertical-transparent
    'layout_light_sidebar' => null,
    'layout_light_topbar' => true,
    'layout_enable_top_header' => false,
    'sticky_top_nav_bar' => false,
    'classes_body' => '',
];

$urls = [
    'use_route_url' => true,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password.request',
    'password_email_url' => 'password.email',
    'profile_url' => false,
    'setting_url' => false,
];

$display = [
    'display_alert' => false,
];

$navigation = [
    'menu' => [
        // INICIO
        [
            'text' => 'Inicio',
            'icon' => 'ti ti-home',
            // ruta nombrada: 'home' -> routes/web.php name('home')
            'route' => 'home',
            'roles' => ['admin', 'control_escolar', 'servicio_financiero', 'tester']
        ],
        [
            'text' => 'Inicio Alumno',
            'icon' => 'ti ti-home',
            // ruta nombrada: 'alumnos_user.index'
            'route' => 'alumnos_user.index',
            'roles' => ['alumno']
        ],
        [
            'text' => 'Inicio Maestro',
            'icon' => 'ti ti-home',
            // dentro del prefix 'maestros' hay name('dashboard')
            'route' => 'maestros.dashboard',
            'roles' => ['maestro']
        ],

        // TESTER (accesos exclusivos)
        ['header' => '🧪 ACCESOS TESTER', 'roles' => ['tester']],
        ['text' => 'Control Escolar - Alumnos', 'icon' => 'ti ti-users', 'route' => 'alumnos.index', 'roles' => ['tester']],
        ['text' => 'Control Escolar - Solicitudes Exámenes', 'icon' => 'ti ti-file-text', 'route' => 'control_user.index', 'roles' => ['tester']],
        ['text' => 'Control Escolar - Solicitudes Servicios', 'icon' => 'ti ti-file-text', 'route' => 'gestions.index', 'roles' => ['tester']],
        ['text' => 'Control Escolar - Materias', 'icon' => 'ti ti-book', 'route' => 'materias.index', 'roles' => ['tester']],
        ['text' => 'Control Escolar - Grupos', 'icon' => 'ti ti-users-group', 'route' => 'grupos.index', 'roles' => ['tester']],
        ['text' => 'Finanzas - Solicitudes Exámenes', 'icon' => 'ti ti-file-text', 'route' => 'solicitudes-servicios-s.index', 'roles' => ['tester']],
        ['text' => 'Finanzas - Solicitudes Servicios', 'icon' => 'ti ti-currency-dollar', 'route' => 'finanzas.index', 'roles' => ['tester']],
        ['text' => 'General - Novedades', 'icon' => 'ti ti-photo', 'route' => 'carrusel.index', 'roles' => ['tester']],
        ['text' => 'Especialidades', 'icon' => 'ti ti-tag', 'route' => 'especialidades.index', 'roles' => ['tester']],

        // ALUMNO
        ['header' => 'FUNCIONALIDADES ALUMNO', 'roles' => ['alumno']],
        ['text' => 'Nueva solicitud de pago de exámenes', 'icon' => 'ti ti-file-plus', 'route' => 'formulario', 'roles' => ['alumno']],
        ['text' => 'Nueva solicitud de servicios', 'icon' => 'ti ti-file-plus', 'route' => 'servicios', 'roles' => ['alumno']],
        ['text' => 'Ver Solicitudes de Exámenes', 'icon' => 'ti ti-list', 'route' => 'solicitudesE.index', 'roles' => ['alumno']],
        ['text' => 'Ver Solicitudes de Servicios', 'icon' => 'ti ti-list', 'route' => 'formularios.index', 'roles' => ['alumno']],
        ['text' => 'Mis Tareas', 'icon' => 'ti ti-checklist', 'route' => 'estudiantes.tareas', 'roles' => ['alumno']],
        ['text' => 'Mis Horarios', 'icon' => 'ti ti-calendar', 'route' => 'estudiantes.horarios', 'roles' => ['alumno']],
        ['text' => 'Mi Kardex Académico', 'icon' => 'ti ti-school', 'route' => 'calificaciones.kardex', 'roles' => ['alumno']],
        ['text' => 'Salas Disponibles', 'icon' => 'ti ti-building', 'route' => 'estudiantes.salas', 'roles' => ['alumno']],
        ['text' => 'Reuniones Virtuales', 'icon' => 'ti ti-video', 'route' => 'alumnos.reuniones.index', 'roles' => ['alumno']],
        ['text' => 'Biblioteca Virtual', 'icon' => 'ti ti-book-2', 'route' => 'biblioteca-virtual.estudiantes', 'roles' => ['alumno']],
        ['text' => 'Documentación', 'icon' => 'ti ti-file-text', 'route' => 'documentos.create', 'roles' => ['alumno']],

        // CONTROL ESCOLAR
        ['header' => 'CONTROL ESCOLAR', 'roles' => ['control_escolar', 'admin']],
        ['text' => 'Alumnos', 'icon' => 'ti ti-users', 'route' => 'alumnos.index', 'roles' => ['control_escolar', 'admin']],
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
        [
            'text' => 'Biblioteca Virtual',
            'url' => 'biblioteca-virtual',
            'icon' => 'ti ti-book-2',
            'roles' => ['control_escolar', 'admin']
        ],
        [
            'text' => 'Revisión de Documentos',
            'url' => 'control_documentos',
            'icon' => 'ti ti-files',
            // incluir ambas variantes de nombre de rol por compatibilidad
            'roles' => ['control_escolar', 'controlescolar', 'admin']
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
        ],
        [
            'text' => 'Biblioteca Virtual',
            'url' => 'biblioteca-virtual',
            'icon' => 'ti ti-book-2',
            'roles' => ['maestro']
        ]
    ],

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
];

$integrations = [
    'vite' => true,
    'livewire' => false,
];

// Retornamos estructura agrupada + claves planas legacy para compatibilidad
return [
    // Agrupaciones
    'brand' => $brand,
    'layout_settings' => $layout,
    'urls' => $urls,
    'display' => $display,
    'navigation' => $navigation,
    'integrations' => $integrations,

    // Claves planas (compatibilidad hacia atrás)
    'title' => $brand['title'],
    'title_prefix' => $brand['title_prefix'],
    'title_postfix' => $brand['title_postfix'],
    'bottom_title' => $brand['bottom_title'],
    'current_version' => $brand['current_version'],
    'logo' => $brand['logo'],
    'logo_img_alt' => $brand['logo_img_alt'],
    'auth_logo' => $brand['auth_logo'],

    'views_path' => $layout['views_path'],
    'layout' => $layout['layout'],
    'layout_light_sidebar' => $layout['layout_light_sidebar'],
    'layout_light_topbar' => $layout['layout_light_topbar'],
    'layout_enable_top_header' => $layout['layout_enable_top_header'],
    'sticky_top_nav_bar' => $layout['sticky_top_nav_bar'],
    'classes_body' => $layout['classes_body'],

    'use_route_url' => $urls['use_route_url'],
    'dashboard_url' => $urls['dashboard_url'],
    'logout_url' => $urls['logout_url'],
    'login_url' => $urls['login_url'],
    'register_url' => $urls['register_url'],
    'password_reset_url' => $urls['password_reset_url'],
    'password_email_url' => $urls['password_email_url'],
    'profile_url' => $urls['profile_url'],
    'setting_url' => $urls['setting_url'],

    'display_alert' => $display['display_alert'],

    // Navegación y filtros (mantener nombres originales también)
    'menu' => $navigation['menu'],
    'filters' => $navigation['filters'],

    // Integraciones
    'vite' => $integrations['vite'],
    'livewire' => $integrations['livewire'],
];