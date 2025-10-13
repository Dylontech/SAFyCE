<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriasTable extends Migration
{
    public function up()
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('materia');
            $table->string('semestre')->nullable(); // Permite valores nulos
            $table->string('especialidad')->nullable(); // Permite valores nulos
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materias');
    }
}
