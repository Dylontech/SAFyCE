<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->morphs('reportable'); // Puede ser publicacion, comentario, perfil
            $table->foreignId('reportado_por')->constrained('alumnos')->onDelete('cascade'); // Alumno que reporta
            $table->enum('tipo_reporte', [
                'contenido_inapropiado',
                'acoso_bullying', 
                'spam',
                'informacion_falsa',
                'violencia',
                'contenido_sexual',
                'drogas_alcohol',
                'otros'
            ]);
            $table->text('descripcion');
            $table->json('evidencias')->nullable(); // Screenshots, URLs, etc.
            $table->enum('estado', ['pendiente', 'en_revision', 'resuelto', 'rechazado'])->default('pendiente');
            $table->foreignId('asignado_a')->nullable()->constrained('users')->onDelete('set null'); // Moderador asignado
            $table->text('respuesta_moderador')->nullable();
            $table->enum('accion_tomada', [
                'sin_accion',
                'advertencia',
                'eliminacion_contenido',
                'bloqueo_temporal',
                'bloqueo_permanente'
            ])->nullable();
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();
            
            $table->index(['estado', 'created_at']);
            // No agregamos el índice morphs aquí porque ya se crea automáticamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
