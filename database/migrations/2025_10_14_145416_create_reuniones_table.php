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
        Schema::create('reuniones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('duracion')->comment('Duración en minutos');
            $table->enum('plataforma', ['meet', 'zoom', 'teams', 'webex'])->default('meet');
            $table->string('enlace_reunion')->nullable();
            $table->string('codigo_reunion')->nullable();
            $table->enum('tipo', ['clase', 'tutorial', 'reunion', 'examen', 'otro'])->default('clase');
            $table->enum('estado', ['activa', 'iniciada', 'finalizada', 'cancelada'])->default('activa');
            $table->integer('max_participantes')->nullable();
            $table->integer('participantes_actuales')->default(0);
            
            // Relaciones
            $table->foreignId('sala_id')->nullable()->constrained('salas')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Índices
            $table->index(['fecha', 'hora']);
            $table->index(['estado', 'fecha']);
            $table->index('user_id');
            $table->index('sala_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniones');
    }
};
