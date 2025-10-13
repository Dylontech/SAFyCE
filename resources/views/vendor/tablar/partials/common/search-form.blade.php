<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Botón de Ayuda por WhatsApp</title>
    <style>
        .btn-whatsapp {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            z-index: 1000;
        }
        .btn-whatsapp svg {
            width: 30px;
            height: 30px;
        }
        .btn-filter {
            background-color: #4CAF50; /* Color verde */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        #results {
            margin-top: 20px;
        }
        .help-text {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f1f1f1;
            color: black;
            border-radius: 5px;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!--
    @if (Auth::check() && Auth::user()->hasRole('alumno'))
         Botones exclusivos para alumnos 
        <a href="{{ route('solicitudesE.index') }}" class="btn-filter">Ver Solicitudes de Exámenes</a>
        <a href="{{ route('formularios.index') }}" class="btn-filter">Ver Solicitudes de Servicios</a>
    @elseif (Auth::check() && Auth::user()->hasRole('control_escolar') && !Auth::user()->hasRole('admin'))
         Botones para control escolar, excluyendo admin 
        <a href="{{ route('control_user.index') }}" class="btn-filter">Solicitudes de Examenes</a>
        <a href="{{ route('gestions.index') }}" class="btn-filter">Solicitudes de Servicios</a>
    @elseif (Auth::check() && Auth::user()->hasRole('servicio_financiero'))
         Botón para servicio financiero 
       
        <a href="{{ route('solicitudes-servicios-s.index') }}" class="btn-filter">Ver Solicitudes de Examenes</a>
        <a href="{{ route('finanzas.index') }}" class="btn-filter">Solicitudes de Servicios Financieros</a>
    @endif -->

    <div id="results"></div>

    @if (!empty($whatsappSettings->phone_number))
        @if (!empty($whatsappSettings->message))
            <a href="https://wa.me/{{ $whatsappSettings->phone_number }}?text={{ urlencode($whatsappSettings->message) }}" class="btn-whatsapp" target="_blank" rel="noreferrer">
                <!-- WhatsApp SVG icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24V24H0z" fill="none"/>
                    <path d="M3 21l1.65 -4.75a9 9 0 1 1 3.1 3.1l-4.75 1.65m10.5 -11.65a3 3 0 1 0 -4 4l1 1l-1 3l3 -1l1 -1a3 3 0 0 0 1 -4"/>
                </svg>
            </a>
        @else
            <div class="help-text">
                En caso de requerir ayuda llamar a: {{ $whatsappSettings->phone_number }}
            </div>
        @endif
    @endif
</body>
</html>
