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
        Schema::create('usuarios_bloqueados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('bloqueado_por')->constrained('users')->onDelete('cascade'); // Usuario que bloqueó
            $table->enum('tipo_bloqueo', ['temporal', 'permanente'])->default('temporal');
            $table->text('motivo');
            $table->text('detalles')->nullable();
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable(); // null para permanente
            $table->enum('estado', ['activo', 'levantado', 'expirado'])->default('activo');
            $table->timestamp('fecha_levantamiento')->nullable();
            $table->foreignId('levantado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->text('razon_levantamiento')->nullable();
            $table->timestamps();
            
            $table->index(['alumno_id', 'estado']);
            $table->index('fecha_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_bloqueados');
    }
};
