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
        Schema::create('redes_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perfil_id')->constrained('perfiles')->onDelete('cascade');
            $table->enum('plataforma', ['instagram', 'twitter', 'facebook', 'tiktok', 'youtube', 'linkedin', 'github', 'discord']);
            $table->string('usuario')->nullable();
            $table->string('url')->nullable();
            $table->boolean('visible')->default(true);
            $table->timestamps();
            
            $table->unique(['perfil_id', 'plataforma']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redes_sociales');
    }
};
