<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Materia;
use App\Models\Tarea;
use App\Models\Calificacion;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CalificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds to simulate tareas (kardex) and calificaciones
     * for periodo escolar 2024-2025 (ambos semestres: -1 y -2).
     */
    public function run(): void
    {
        $this->command->info('Generando tareas y calificaciones para periodo 2024-2025...');

        $periodos = ['2024-2025-1', '2024-2025-2'];

        $alumnos = Alumno::all();
        $usuariosMaestro = User::role('maestro')->get()->values();

        if ($alumnos->isEmpty() || $usuariosMaestro->isEmpty()) {
            $this->command->warn('No hay alumnos o maestros suficientes para generar calificaciones. Ejecuta seeders previos.');
            return;
        }

        // Permitir controlar el número máximo de alumnos mediante la variable de entorno MAX_ALUMNOS
        $envMax = env('MAX_ALUMNOS');
        if ($envMax && is_numeric($envMax) && $envMax > 0) {
            $maxAlumnos = min((int)$envMax, $alumnos->count());
        } else {
            // Si no se proporciona, procesar todos los alumnos
            $maxAlumnos = $alumnos->count();
        }

        $alumnos->shuffle();
        $alumnos = $alumnos->slice(0, $maxAlumnos);

        foreach ($alumnos as $alumno) {
            // Obtener materias del alumno según su especialidad y semestre
            $materias = $alumno->materias();
            if ($materias->isEmpty()) continue;

            foreach ($materias as $materia) {
                // Elegir un maestro aleatorio que tenga relación (fallback al primero)
                $maestro = $usuariosMaestro->random();

                // Para cada periodo generar varias tareas y evaluaciones
                foreach ($periodos as $periodo) {
                    // Crear 3 tareas tipo 'tarea' por materia/periodo
                    for ($t = 1; $t <= 3; $t++) {
                        $titulo = "Tarea {$t} - {$materia->materia} - {$periodo}";
                        $tarea = Tarea::updateOrCreate(
                            ['titulo' => $titulo],
                            [
                                'descripcion' => "Kardex: ejercicios T{$t} de {$materia->materia}",
                                'materia_id' => $materia->id,
                                'maestro_id' => $maestro->id,
                                'grupo' => $alumno->Grupo ?? '1A',
                                'semestre' => $alumno->semestre,
                                'fecha_entrega' => now()->subDays(rand(0, 200)),
                                'puntos_totales' => 100,
                                'tipo' => 'tarea',
                                'estado' => 'activa',
                                'instrucciones' => 'Entrega por plataforma'
                            ]
                        );

                        // Crear calificacion asociada a la tarea
                        $puntosObtenidos = rand(40, 100);
                        $cal = Calificacion::updateOrCreate(
                            [
                                'alumno_id' => $alumno->id,
                                'tarea_id' => $tarea->id
                            ],
                            [
                                'materia_id' => $materia->id,
                                'maestro_id' => $maestro->id,
                                'puntos_obtenidos' => $puntosObtenidos,
                                'puntos_totales' => 100,
                                'calificacion' => round(($puntosObtenidos / 100) * 100, 2),
                                'tipo_evaluacion' => 'tarea',
                                'periodo_escolar' => $periodo,
                                'parcial' => $t,
                                'fecha_evaluacion' => now()->subDays(rand(0, 200))
                            ]
                        );
                    }

                    // Crear un examen parcial y final para la materia en este periodo
                    $parcialTitulo = "Examen Parcial - {$materia->materia} - {$periodo}";
                    $parcial = Calificacion::updateOrCreate(
                        [
                            'alumno_id' => $alumno->id,
                            'tipo_evaluacion' => 'examen_parcial',
                            'periodo_escolar' => $periodo,
                            'materia_id' => $materia->id
                        ],
                        [
                            'maestro_id' => $maestro->id,
                            'calificacion' => rand(50, 95),
                            'fecha_evaluacion' => now()->subDays(rand(0, 200)),
                            'parcial' => 1
                        ]
                    );

                    $final = Calificacion::updateOrCreate(
                        [
                            'alumno_id' => $alumno->id,
                            'tipo_evaluacion' => 'examen_final',
                            'periodo_escolar' => $periodo,
                            'materia_id' => $materia->id
                        ],
                        [
                            'maestro_id' => $maestro->id,
                            'calificacion' => rand(50, 95),
                            'fecha_evaluacion' => now()->subDays(rand(0, 200)),
                            'parcial' => null
                        ]
                    );
                }
            }
        }

        $this->command->info('Simulación de tareas y calificaciones para 2024-2025 completada.');
    }
}
