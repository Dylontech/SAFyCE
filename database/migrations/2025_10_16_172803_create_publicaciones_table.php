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
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perfil_id')->constrained('perfiles')->onDelete('cascade');
            $table->text('contenido');
            $table->enum('tipo', ['texto', 'imagen', 'archivo', 'galeria'])->default('texto');
            $table->json('archivos')->nullable();
            $table->json('etiquetas')->nullable();
            $table->boolean('activa')->default(true);
            $table->integer('total_reacciones')->default(0);
            $table->integer('total_comentarios')->default(0);
            $table->timestamps();
            
            $table->index(['perfil_id', 'activa']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
