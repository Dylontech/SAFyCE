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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('maestro_id')->constrained('users')->onDelete('cascade'); // Maestro que asigna la tarea
            $table->string('grupo'); // Grupo al que se asigna: "6A", "4B", etc.
            $table->enum('semestre', ['1', '2', '3', '4', '5', '6', '7', '8']);
            $table->datetime('fecha_asignacion')->default(now());
            $table->datetime('fecha_entrega');
            $table->integer('puntos_totales')->default(100); // Puntos máximos de la tarea
            $table->enum('tipo', ['tarea', 'proyecto', 'examen', 'practica', 'ensayo'])->default('tarea');
            $table->enum('estado', ['activa', 'vencida', 'cancelada'])->default('activa');
            $table->text('instrucciones')->nullable(); // Instrucciones adicionales
            $table->string('archivo_adjunto')->nullable(); // Ruta del archivo adjunto
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['grupo', 'semestre']);
            $table->index(['fecha_entrega', 'estado']);
            $table->index(['maestro_id', 'materia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
