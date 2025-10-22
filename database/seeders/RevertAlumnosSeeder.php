<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;

class RevertAlumnosSeeder extends Seeder
{
    /**
     * Run the database seeds to revert the heavy AlumnosSeeder.
     * This truncates the `alumnos` table and, if available, re-runs
     * `AlumnosTableSeeder` to restore a baseline set of records.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('⏳ Revirtiendo seed de alumnos...');

        // Desactivar verificaciones FK para truncar de forma segura
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Alumno::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('✔️ Tabla `alumnos` truncada.');

        // Si existe el seeder base, reejecutarlo para dejar datos de prueba controlados
        if (class_exists(AlumnosTableSeeder::class)) {
            $this->call(AlumnosTableSeeder::class);
            $this->command->info('✔️ Alumnos base restaurados con AlumnosTableSeeder.');
        } else {
            $this->command->warn('⚠️ AlumnosTableSeeder no encontrada; la tabla quedó vacía.');
        }
    }
}
