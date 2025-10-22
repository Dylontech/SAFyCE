<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use App\Models\Asistencia;
use App\Models\Alumno;
use Illuminate\Support\Carbon;

class AsistenciasSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Generando asistencias de ejemplo...');

        // Configurables via env
        $maxDays = env('MAX_DAYS', 30); // cuántos días hacia atrás generar por horario
        $maxAlumnosPorClase = env('MAX_ALUMNOS_POR_CLASE', 20);
        $startDateEnv = env('ASISTENCIAS_START_DATE'); // formato YYYY-MM-DD opcional

        $horarios = Horario::all();
        if ($horarios->isEmpty()) {
            $this->command->warn('No hay horarios disponibles para generar asistencias.');
            return;
        }

        $today = Carbon::now()->startOfDay();
        $startDate = $startDateEnv ? Carbon::parse($startDateEnv) : $today->copy()->subDays($maxDays);

        foreach ($horarios as $horario) {
            // Obtener lista de alumnos que podrían tomar la materia del horario
            try {
                $alumnos = Alumno::where('especialidad', $horario->materia->especialidad ?? null)
                                  ->where('semestre', $horario->semestre ?? null)
                                  ->get();
            } catch (\Exception $e) {
                // Fallback: tomar alumnos aleatorios
                $alumnos = Alumno::inRandomOrder()->take($maxAlumnosPorClase)->get();
            }

            if ($alumnos->isEmpty()) {
                // tomar algunos alumnos aleatorios si no hay coincidencias
                $alumnos = Alumno::inRandomOrder()->take($maxAlumnosPorClase)->get();
            }

            $alumnos = $alumnos->shuffle()->slice(0, $maxAlumnosPorClase);

            // Generar por cada día desde startDate hasta hoy (solo días con mismo dia_semana que el horario)
                $period = \Carbon\CarbonPeriod::create($startDate, $today);
            foreach ($period as $date) {
                // Verificar si el dia coincide con el dia_semana del horario
                $diaSemana = strtolower($date->format('l')); // devuelve 'monday'.. en inglés
                $mapDias = [
                    'monday' => 'lunes','tuesday' => 'martes','wednesday' => 'miercoles',
                    'thursday' => 'jueves','friday' => 'viernes','saturday' => 'sabado','sunday' => 'domingo'
                ];
                if (!isset($mapDias[$diaSemana]) || $mapDias[$diaSemana] !== $horario->dia_semana) {
                    continue;
                }

                foreach ($alumnos as $alumno) {
                    // Probabilidad de asistencia (ajustable)
                    $rand = rand(1, 100);
                    // Mapear a los estados permitidos por la migración: 'presente','falta','justificada'
                    if ($rand <= 85) {
                        $estado = 'presente';
                        $horaEntrada = $date->copy()->setTimeFromTimeString($horario->hora_inicio)->addMinutes(rand(0, 5));
                        $motivo = null;
                    } elseif ($rand <= 95) {
                        // llegadas tarde serán marcadas como presentes pero con motivo 'tarde'
                        $estado = 'presente';
                        $horaEntrada = $date->copy()->setTimeFromTimeString($horario->hora_inicio)->addMinutes(rand(6, 30));
                        $motivo = 'tarde';
                    } else {
                        // ausencias -> 'falta' o 'justificada' aleatoriamente
                        $horaEntrada = null;
                        $motivo = 'Inasistencia';
                        $estado = rand(0,1) ? 'falta' : 'justificada';
                    }

                    // Clave única: alumno_id + fecha + maestro_id + sala_id
                    $unique = [
                        'alumno_id' => $alumno->id,
                        'fecha' => $date->toDateString(),
                        'maestro_id' => $horario->user_id ?? $horario->maestro_id ?? null,
                        'grupo_id' => null
                    ];

                    $data = [
                        'alumno_id' => $alumno->id,
                        'grupo_id' => null,
                        'maestro_id' => $horario->user_id ?? $horario->maestro_id ?? null,
                        // nivel limitado a 'plantel' o 'salon' según migración; usar plantel por defecto
                        'nivel' => 'plantel',
                        'fecha' => $date->toDateString(),
                        'hora_entrada' => $horaEntrada,
                        'codigo_barra' => null,
                        'estado' => $estado,
                        'motivo' => $motivo,
                    ];

                    Asistencia::updateOrCreate($unique, $data);
                }
            }
        }

        $this->command->info('Asistencias generadas.');
    }
}
