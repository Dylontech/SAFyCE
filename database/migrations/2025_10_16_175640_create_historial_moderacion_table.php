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
        Schema::create('historial_moderacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moderador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('alumno_afectado')->nullable()->constrained('alumnos')->onDelete('cascade');
            $table->string('contenido_afectado_type')->nullable(); // Tipo de contenido
            $table->unsignedBigInteger('contenido_afectado_id')->nullable(); // ID del contenido
            $table->enum('accion', [
                'eliminar_publicacion',
                'eliminar_comentario',
                'bloquear_usuario',
                'desbloquear_usuario',
                'advertencia',
                'revision_reporte'
            ]);
            $table->text('motivo');
            $table->text('detalles')->nullable();
            $table->json('datos_adicionales')->nullable(); // Backup del contenido eliminado
            $table->string('ip_moderador')->nullable();
            $table->timestamps();
            
            $table->index(['moderador_id', 'created_at']);
            $table->index(['alumno_afectado', 'accion']);
            $table->index(['contenido_afectado_type', 'contenido_afectado_id'], 'historial_contenido_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_moderacion');
    }
};
