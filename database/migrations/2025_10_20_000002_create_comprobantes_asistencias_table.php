<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes_asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asistencia_id')->nullable();
            $table->unsignedBigInteger('alumno_id');
            $table->string('archivo');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('revisado_por')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('asistencia_id')->references('id')->on('asistencias')->onDelete('set null');
            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->foreign('revisado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes_asistencias');
    }
};
