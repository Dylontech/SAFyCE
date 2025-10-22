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
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->string('icono_personalizado')->default('student-default');
            $table->text('biografia')->nullable();
            $table->string('estado')->nullable();
            $table->json('configuracion_privacidad')->nullable();
            $table->json('materias_favoritas')->nullable();
            $table->integer('puntos_actividad')->default(0);
            $table->timestamps();
            
            $table->unique('alumno_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfiles');
    }
};
