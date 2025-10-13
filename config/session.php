<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Controlador de Sesión Predeterminado
    |--------------------------------------------------------------------------
    |
    | Esta opción determina el controlador de sesión predeterminado que se
    | utiliza para las solicitudes entrantes. Laravel soporta una variedad
    | de opciones de almacenamiento para persistir los datos de la sesión.
    | El almacenamiento en base de datos es una excelente opción por defecto.
    |
    | Soportado: "file", "cookie", "database", "apc",
    |            "memcached", "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Duración de la Sesión
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar el número de minutos que deseas que la sesión
    | permanezca inactiva antes de que expire. Si quieres que expiren
    | inmediatamente cuando se cierre el navegador, puedes indicarlo
    | a través de la opción expire_on_close.
    |
    */

    'lifetime' => env('SESSION_LIFETIME', 30),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', true),

    /*
    |--------------------------------------------------------------------------
    | Encriptación de la Sesión
    |--------------------------------------------------------------------------
    |
    | Esta opción te permite especificar fácilmente que todos los datos de tu
    | sesión deben ser encriptados antes de ser almacenados. Laravel realiza
    | toda la encriptación automáticamente y puedes usar la sesión como de
    | costumbre.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', true),

    /*
    |--------------------------------------------------------------------------
    | Ubicación de Archivos de Sesión
    |--------------------------------------------------------------------------
    |
    | Cuando utilizas el controlador de sesión "file", los archivos de sesión
    | se colocan en el disco. La ubicación de almacenamiento predeterminada se
    | define aquí; sin embargo, eres libre de proporcionar otra ubicación
    | donde deberían ser almacenados.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Conexión a Base de Datos de Sesión
    |--------------------------------------------------------------------------
    |
    | Cuando utilizas los controladores de sesión "database" o "redis", puedes
    | especificar una conexión que se utilizará para gestionar estas sesiones.
    | Esto debería corresponder a una conexión en tus opciones de configuración
    | de base de datos.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Tabla de Base de Datos de Sesión
    |--------------------------------------------------------------------------
    |
    | Cuando utilizas el controlador de sesión "database", puedes especificar la
    | tabla que se utilizará para almacenar las sesiones. Por supuesto, se
    | define un valor predeterminado sensato para ti; sin embargo, puedes
    | cambiar esto a otra tabla.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Almacén de Caché de Sesión
    |--------------------------------------------------------------------------
    |
    | Cuando utilizas uno de los backends de sesión controlados por caché del
    | framework, puedes definir el almacén de caché que se utilizará para
    | almacenar los datos de la sesión entre solicitudes. Esto debe coincidir
    | con uno de tus almacenes de caché definidos.
    |
    | Afecta: "apc", "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Lotería de Barrido de Sesión
    |--------------------------------------------------------------------------
    |
    | Algunos controladores de sesión deben barrer manualmente su ubicación de
    | almacenamiento para deshacerse de las sesiones antiguas. Aquí están las
    | probabilidades de que esto ocurra en una solicitud dada. Por defecto, las
    | probabilidades son 2 de 100.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Nombre de la Cookie de Sesión
    |--------------------------------------------------------------------------
    |
    | Aquí puedes cambiar el nombre de la cookie de sesión que crea el
    | framework. Por lo general, no deberías necesitar cambiar este valor ya
    | que hacerlo no otorga una mejora de seguridad significativa.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Ruta de la Cookie de Sesión
    |--------------------------------------------------------------------------
    |
    | La ruta de la cookie de sesión determina la ruta en la que la cookie se
    | considerará disponible. Por lo general, será la ruta raíz de tu
    | aplicación, pero eres libre de cambiar esto cuando sea necesario.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Dominio de la Cookie de Sesión
    |--------------------------------------------------------------------------
    |
    | Este valor determina el dominio y los subdominios en los que la cookie de
    | sesión está disponible. Por defecto, la cookie estará disponible para el
    | dominio raíz y todos los subdominios. Por lo general, esto no debería
    | cambiarse.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Cookies Solo HTTPS
    |--------------------------------------------------------------------------
    |
    | Al configurar esta opción en true, las cookies de sesión solo se enviarán
    | de vuelta al servidor si el navegador tiene una conexión HTTPS. Esto
    | evitará que la cookie se envíe cuando no se pueda hacer de forma segura.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | Solo Acceso HTTP
    |--------------------------------------------------------------------------
    |
    | Configurar este valor en true evitará que JavaScript acceda al valor de la
    | cookie y la cookie solo será accesible a través del protocolo HTTP. Es
    | poco probable que debas desactivar esta opción.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cookies Same-Site
    |--------------------------------------------------------------------------
    |
    | Esta opción determina cómo se comportan tus cookies cuando se realizan
    | solicitudes entre sitios y puede utilizarse para mitigar ataques CSRF.
    | Por defecto, configuramos este valor en "lax" para permitir solicitudes
    | entre sitios seguras.
    |
    | Ver: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Soportado: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cookies Particionadas
    |--------------------------------------------------------------------------
    |
    | Configurar este valor en true vinculará la cookie al sitio de nivel superior
    | para un contexto entre sitios. Las cookies particionadas son aceptadas por
    | el navegador cuando se marcan como "seguras" y el atributo Same-Site está
    | configurado en "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
