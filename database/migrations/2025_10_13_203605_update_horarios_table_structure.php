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
        Schema::table('horarios', function (Blueprint $table) {
            // Cambiar maestro_id por user_id si existe
            if (Schema::hasColumn('horarios', 'maestro_id')) {
                $table->renameColumn('maestro_id', 'user_id');
            }
            
            // Agregar nuevas columnas si no existen
            if (!Schema::hasColumn('horarios', 'fecha_inicio')) {
                $table->date('fecha_inicio');
            }
            if (!Schema::hasColumn('horarios', 'fecha_fin')) {
                $table->date('fecha_fin');
            }
            if (!Schema::hasColumn('horarios', 'observaciones')) {
                $table->text('observaciones')->nullable();
            }
            
            // Eliminar columnas que ya no se usan
            if (Schema::hasColumn('horarios', 'grupo')) {
                $table->dropColumn('grupo');
            }
            if (Schema::hasColumn('horarios', 'semestre')) {
                $table->dropColumn('semestre');
            }
            if (Schema::hasColumn('horarios', 'periodo_escolar')) {
                $table->dropColumn('periodo_escolar');
            }
            if (Schema::hasColumn('horarios', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            // Revertir cambios
            if (Schema::hasColumn('horarios', 'user_id')) {
                $table->renameColumn('user_id', 'maestro_id');
            }
            
            if (Schema::hasColumn('horarios', 'fecha_inicio')) {
                $table->dropColumn('fecha_inicio');
            }
            if (Schema::hasColumn('horarios', 'fecha_fin')) {
                $table->dropColumn('fecha_fin');
            }
            if (Schema::hasColumn('horarios', 'observaciones')) {
                $table->dropColumn('observaciones');
            }
            
            // Volver a agregar columnas antiguas
            $table->string('grupo', 10)->nullable();
            $table->enum('semestre', ['1','2','3','4','5','6','7','8'])->nullable();
            $table->string('periodo_escolar', 20)->nullable();
            $table->enum('estado', ['activo','suspendido','finalizado'])->default('activo');
        });
    }
};
