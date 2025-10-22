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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('maestro_id')->constrained('users')->onDelete('cascade'); // Maestro que imparte la materia
            $table->foreignId('sala_id')->constrained('salas')->onDelete('cascade');
            $table->string('grupo'); // Ej: "6A", "4B"
            $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('semestre', ['1', '2', '3', '4', '5', '6', '7', '8']);
            $table->string('periodo_escolar'); // Ej: "2024-2025-1", "2024-2025-2"
            $table->enum('estado', ['activo', 'suspendido', 'finalizado'])->default('activo');
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['dia_semana', 'hora_inicio', 'hora_fin']);
            $table->index(['grupo', 'semestre']);
            $table->index(['periodo_escolar', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
