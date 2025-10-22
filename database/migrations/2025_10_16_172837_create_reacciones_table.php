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
        Schema::create('reacciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publicacion_id')->constrained('publicaciones')->onDelete('cascade');
            $table->foreignId('perfil_id')->constrained('perfiles')->onDelete('cascade');
            $table->enum('tipo', ['like', 'love', 'wow', 'funny', 'sad', 'angry'])->default('like');
            $table->timestamps();
            
            $table->unique(['publicacion_id', 'perfil_id']);
            $table->index(['publicacion_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reacciones');
    }
};
