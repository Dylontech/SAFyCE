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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->integer('semestre')->comment('Número del semestre (1-12)');
            $table->string('letra', 10)->comment('Letra del grupo (a, b, c, etc.)');
            $table->string('nombre_completo', 50)->unique()->comment('Nombre completo del grupo (1a, 1b, 2c, etc.)');
            $table->boolean('activo')->default(true)->comment('Si el grupo está activo o no');
            $table->timestamps();

            // Índices para mejorar las consultas
            $table->index(['semestre', 'letra']);
            $table->index('activo');
            
            // Índice único compuesto para evitar duplicados
            $table->unique(['semestre', 'letra'], 'grupos_semestre_letra_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
