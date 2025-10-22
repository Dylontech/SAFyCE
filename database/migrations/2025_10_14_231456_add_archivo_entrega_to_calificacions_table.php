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
        Schema::table('calificacions', function (Blueprint $table) {
            $table->string('archivo_entrega')->nullable()->after('comentarios');
            $table->datetime('fecha_entrega_alumno')->nullable()->after('archivo_entrega');
            $table->enum('estado_entrega', ['pendiente', 'entregada', 'tarde', 'calificada'])->default('pendiente')->after('fecha_entrega_alumno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calificacions', function (Blueprint $table) {
            $table->dropColumn(['archivo_entrega', 'fecha_entrega_alumno', 'estado_entrega']);
        });
    }
};
