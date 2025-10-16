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
        Schema::create('biblioteca_virtual', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('url');
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();
            $table->string('categoria')->default('general');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
            
            $table->index(['activo', 'orden']);
            $table->index('categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biblioteca_virtual');
    }
};
