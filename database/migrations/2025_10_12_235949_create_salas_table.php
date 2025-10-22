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
        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Ej: "Aula 101", "Laboratorio de Computación"
            $table->string('codigo')->unique(); // Ej: "A101", "LAB-COMP"
            $table->integer('capacidad'); // Número máximo de estudiantes
            $table->enum('tipo', ['aula', 'laboratorio', 'taller', 'auditorio', 'sala_de_juntas']);
            $table->text('descripcion')->nullable(); // Equipamiento, características especiales
            $table->enum('estado', ['disponible', 'ocupada', 'mantenimiento', 'fuera_de_servicio'])->default('disponible');
            $table->string('ubicacion')->nullable(); // Piso, edificio, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
