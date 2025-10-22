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
        Schema::create('calificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('tarea_id')->nullable()->constrained('tareas')->onDelete('set null'); // Puede ser null para calificaciones generales
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('maestro_id')->constrained('users')->onDelete('cascade'); // Maestro que califica
            $table->decimal('calificacion', 5, 2); // Calificación obtenida (ej: 85.50)
            $table->integer('puntos_obtenidos')->nullable(); // Puntos obtenidos en la tarea
            $table->integer('puntos_totales')->nullable(); // Puntos totales de la tarea
            $table->enum('tipo_evaluacion', ['tarea', 'examen_parcial', 'examen_final', 'proyecto', 'participacion', 'practica']);
            $table->string('periodo_escolar'); // Ej: "2024-2025-1"
            $table->integer('parcial')->nullable(); // 1, 2, 3 para indicar el parcial
            $table->text('comentarios')->nullable(); // Retroalimentación del maestro
            $table->datetime('fecha_evaluacion')->default(now());
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['alumno_id', 'materia_id', 'periodo_escolar']);
            $table->index(['tipo_evaluacion', 'parcial']);
            $table->index(['maestro_id', 'materia_id']);
            
            // Constraint para evitar calificaciones duplicadas por tarea-alumno
            $table->unique(['alumno_id', 'tarea_id'], 'unique_alumno_tarea');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificacions');
    }
};
