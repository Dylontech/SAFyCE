<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ← AÑADE ESTA LÍNEA

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagina_inicio', function (Blueprint $table) {
            $table->id();
            $table->string('titulo_principal');
            $table->text('descripcion');
            $table->string('imagen_principal')->nullable();
            $table->string('titulo_equipo');
            $table->text('miembros_equipo'); // JSON con información del equipo
            $table->string('titulo_novedades');
            $table->text('novedades'); // JSON con novedades
            $table->string('titulo_contacto');
            $table->string('email_contacto');
            $table->string('telefono_contacto');
            $table->string('direccion_contacto');
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable(); // ← CAMBIADO: twitter por whatsapp
            $table->string('instagram')->nullable();
            $table->text('about')->nullable(); // Nueva columna para "About"
            $table->timestamps();
        });

        // Insertar datos iniciales
        DB::table('pagina_inicio')->insert([
            'titulo_principal' => 'Sistema de Gestión Escolar',
            'descripcion' => 'Plataforma integral para la administración de procesos académicos y administrativos',
            'imagen_principal' => null,
            'titulo_equipo' => 'Nuestro Equipo de Desarrollo',
            'miembros_equipo' => json_encode([
                ['nombre' => 'Juan Pérez', 'cargo' => 'Desarrollador Fullstack', 'foto' => null],
                ['nombre' => 'María García', 'cargo' => 'Diseñadora UX/UI', 'foto' => null],
                ['nombre' => 'Carlos López', 'cargo' => 'Administrador de Base de Datos', 'foto' => null]
            ]),
            'titulo_novedades' => 'Últimas Novedades',
            'novedades' => json_encode([
                ['titulo' => 'Nueva versión del sistema', 'fecha' => '2024-01-15', 'descripcion' => 'Lanzamiento de nuevas funcionalidades'],
                ['titulo' => 'Mantenimiento programado', 'fecha' => '2024-01-20', 'descripcion' => 'El sistema estará en mantenimiento']
            ]),
            'titulo_contacto' => 'Contáctanos',
            'email_contacto' => 'contacto@sistema.edu',
            'telefono_contacto' => '+1 234 567 8900',
            'direccion_contacto' => 'Av. Universidad #123, Ciudad',
            'facebook' => 'https://facebook.com/sistema',
            'whatsapp' => 'https://wa.me/1234567890', // ← CAMBIADO
            'instagram' => 'https://instagram.com/sistema',
            'created_at' => now(),
            'updated_at' => now(),
            'about' =>  json_encode([
                'mision' => 'Proveer una plataforma eficiente y confiable para la gestión escolar.',
                'vision' => 'Ser líderes en soluciones tecnológicas para instituciones educativas.',
                'valores' => ['Innovación', 'Compromiso', 'Calidad', 'Transparencia']
            ])
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('pagina_inicio');
    }
};
