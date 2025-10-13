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
        Schema::create('formulario_e', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->string('nombre');
            $table->string('curp');
            $table->string('numero_control');
            $table->string('especialidad');
            $table->string('numero_lista');
            $table->string('grupo');
            $table->string('tipo_pago'); // ✅ Campo tipo_pago incluido
            $table->date('fecha_pago');
            $table->text('materias'); // ✅ Solo este campo para materias
            $table->string('status')->default('pendiente');
            $table->text('comentario')->nullable();
            $table->text('comentario_financiero')->nullable();
            $table->string('liga_de_pago')->nullable();
            $table->string('comprobante_alumno')->nullable();
            $table->string('comprobante')->nullable();
            $table->string('comprobante_oficial')->nullable();
            $table->timestamps();

            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario_e');
    }
};