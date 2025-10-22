<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (! Schema::hasColumn('alumnos', 'codigo_barra')) {
                $table->string('codigo_barra')->nullable()->after('remember_token');
            }
        });
    }

    public function down()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (Schema::hasColumn('alumnos', 'codigo_barra')) {
                $table->dropColumn('codigo_barra');
            }
        });
    }
};
