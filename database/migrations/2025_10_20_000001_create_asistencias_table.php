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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->unsignedBigInteger('maestro_id')->nullable();
            $table->enum('nivel', ['plantel', 'salon'])->default('plantel');
            $table->date('fecha');
            $table->time('hora_entrada')->nullable();
            $table->string('codigo_barra')->nullable();
            $table->enum('estado', ['presente', 'falta', 'justificada'])->default('presente');
            $table->string('motivo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('set null');
            $table->foreign('maestro_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
};
