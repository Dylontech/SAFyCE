<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Tarea;
use App\Models\Calificacion;
use App\Models\User;
use App\Models\Materia;

class CalificacionesPorAlumnoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Generando calificaciones para un alumno específico...');

        $alumnoId = env('ALUMNO_ID');
        $alumnoNum = env('ALUMNO_NUM');

        if (!$alumnoId && !$alumnoNum) {
            $this->command->error('Define ALUMNO_ID o ALUMNO_NUM en el entorno antes de ejecutar este seeder. Ej: ALUMNO_NUM=25000001 php artisan db:seed --class=CalificacionesPorAlumnoSeeder');
            return;
        }

        $alumno = null;
        if ($alumnoId) {
            $alumno = Alumno::find($alumnoId);
        }
        if (!$alumno && $alumnoNum) {
            $alumno = Alumno::where('numero_control', $alumnoNum)->first();
        }

        if (!$alumno) {
            $this->command->error('Alumno no encontrado con los parámetros dados.');
            return;
        }

        $this->command->info("Alumno seleccionado: {$alumno->Nombre} ({$alumno->numero_control})");

        $maestros = User::role('maestro')->get();
        if ($maestros->isEmpty()) {
            $this->command->error('No hay maestros registrados en el sistema.');
            return;
        }

        $materias = $alumno->materias();
        if ($materias->isEmpty()) {
            $this->command->warn('El alumno no tiene materias asignadas según su especialidad/semestre. Se seleccionarán materias aleatorias como fallback.');
            $materias = Materia::inRandomOrder()->take(4)->get();
            if ($materias->isEmpty()) {
                $this->command->error('No hay materias disponibles en la base de datos para usar como fallback.');
                return;
            }
        }

        $periodos = ['2024-2025-1', '2024-2025-2'];

        foreach ($materias as $materia) {
            $maestro = $maestros->random();
            foreach ($periodos as $periodo) {
                // Crear 2 tareas y una calificación de parcial+final
                for ($t = 1; $t <= 2; $t++) {
                    $titulo = "Tarea {$t} - {$materia->materia} - {$periodo} - {$alumno->numero_control}";
                    $tarea = Tarea::updateOrCreate(['titulo' => $titulo], [
                        'descripcion' => "Tarea para kardex",
                        'materia_id' => $materia->id,
                        'maestro_id' => $maestro->id,
                        'grupo' => $alumno->Grupo ?? '1A',
                        'semestre' => $alumno->semestre,
                        'fecha_entrega' => now()->subDays(30),
                        'puntos_totales' => 100,
                        'tipo' => 'tarea',
                        'estado' => 'activa'
                    ]);

                    $puntos = rand(50, 95);
                    Calificacion::updateOrCreate(
                        ['alumno_id' => $alumno->id, 'tarea_id' => $tarea->id],
                        [
                            'materia_id' => $materia->id,
                            'maestro_id' => $maestro->id,
                            'puntos_obtenidos' => $puntos,
                            'puntos_totales' => 100,
                            'calificacion' => round(($puntos/100)*100,2),
                            'tipo_evaluacion' => 'tarea',
                            'periodo_escolar' => $periodo,
                            'parcial' => $t,
                            'fecha_evaluacion' => now()->subDays(25)
                        ]
                    );
                }

                // Parcial
                Calificacion::updateOrCreate(
                    ['alumno_id' => $alumno->id, 'tipo_evaluacion' => 'examen_parcial', 'materia_id' => $materia->id, 'periodo_escolar' => $periodo],
                    [
                        'maestro_id' => $maestro->id,
                        'calificacion' => rand(55, 95),
                        'fecha_evaluacion' => now()->subDays(20),
                        'parcial' => 1
                    ]
                );

                // Final
                Calificacion::updateOrCreate(
                    ['alumno_id' => $alumno->id, 'tipo_evaluacion' => 'examen_final', 'materia_id' => $materia->id, 'periodo_escolar' => $periodo],
                    [
                        'maestro_id' => $maestro->id,
                        'calificacion' => rand(55, 95),
                        'fecha_evaluacion' => now()->subDays(10)
                    ]
                );
            }
        }

        $this->command->info('Calificaciones generadas para el alumno.');
    }
}
