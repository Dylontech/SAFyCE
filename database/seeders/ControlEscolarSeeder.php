<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sala;
use App\Models\Materia;
use App\Models\Horario;
use App\Models\Tarea;
use App\Models\User;
use App\Models\Alumno;

class ControlEscolarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando datos de prueba para Control Escolar...');

        // Crear salas
        $salas = [
            [
                'nombre' => 'Aula 101',
                'codigo' => 'A101',
                'capacidad' => 40,
                'tipo' => 'aula',
                'descripcion' => 'Aula equipada con proyector y pizarrón inteligente',
                'estado' => 'disponible',
                'ubicacion' => 'Edificio A, Primer Piso'
            ],
            [
                'nombre' => 'Laboratorio de Computación',
                'codigo' => 'LAB-COMP',
                'capacidad' => 30,
                'tipo' => 'laboratorio',
                'descripcion' => '30 computadoras con software de programación',
                'estado' => 'disponible',
                'ubicacion' => 'Edificio B, Segundo Piso'
            ],
            [
                'nombre' => 'Taller de Electrónica',
                'codigo' => 'TALL-ELEC',
                'capacidad' => 25,
                'tipo' => 'taller',
                'descripcion' => 'Equipado con estaciones de soldadura y instrumentos de medición',
                'estado' => 'disponible',
                'ubicacion' => 'Edificio C, Planta Baja'
            ],
            [
                'nombre' => 'Auditorio Principal',
                'codigo' => 'AUD-PRIN',
                'capacidad' => 200,
                'tipo' => 'auditorio',
                'descripcion' => 'Auditorio con sistema de sonido profesional',
                'estado' => 'disponible',
                'ubicacion' => 'Edificio Principal'
            ]
        ];

        foreach ($salas as $salaData) {
            if (!Sala::where('codigo', $salaData['codigo'])->exists()) {
                Sala::create($salaData);
                $this->command->info("Sala creada: {$salaData['nombre']}");
            }
        }

        // Crear materias adicionales si no existen
        $materias = [
            ['materia' => 'Programación I', 'semestre' => '1', 'especialidad' => 'Programación'],
            ['materia' => 'Programación II', 'semestre' => '2', 'especialidad' => 'Programación'],
            ['materia' => 'Base de Datos', 'semestre' => '3', 'especialidad' => 'Programación'],
            ['materia' => 'Matemáticas I', 'semestre' => '1', 'especialidad' => 'Programación'],
            ['materia' => 'Matemáticas II', 'semestre' => '2', 'especialidad' => 'Programación'],
            ['materia' => 'Física I', 'semestre' => '1', 'especialidad' => 'Electrónica'],
            ['materia' => 'Circuitos Eléctricos', 'semestre' => '2', 'especialidad' => 'Electrónica'],
        ];

        foreach ($materias as $materiaData) {
            if (!Materia::where('materia', $materiaData['materia'])
                         ->where('semestre', $materiaData['semestre'])
                         ->where('especialidad', $materiaData['especialidad'])
                         ->exists()) {
                Materia::create($materiaData);
                $this->command->info("Materia creada: {$materiaData['materia']}");
            }
        }

        // Obtener maestros y materias para crear horarios
        $maestros = User::role('maestro')->get();
        $salasDisponibles = Sala::all();
        $materiasDisponibles = Materia::all();

        if ($maestros->count() > 0 && $salasDisponibles->count() > 0 && $materiasDisponibles->count() > 0) {
            // Crear algunos horarios de ejemplo (usamos claves únicas simples para evitar duplicados)
            $horariosEjemplo = [
                [
                    'materia_id' => $materiasDisponibles->where('materia', 'Programación I')->first()->id ?? $materiasDisponibles->first()->id,
                    'user_id' => $maestros->first()->id,
                    'sala_id' => $salasDisponibles->where('codigo', 'LAB-COMP')->first()->id ?? $salasDisponibles->first()->id,
                    'dia_semana' => 'lunes',
                    'hora_inicio' => '08:00',
                    'hora_fin' => '10:00',
                    'fecha_inicio' => '2025-01-15',
                    'fecha_fin' => '2025-12-15',
                    'observaciones' => 'Horario de programación básica'
                ],
                [
                    'materia_id' => $materiasDisponibles->where('materia', 'Programación II')->first()->id ?? $materiasDisponibles->skip(1)->first()->id,
                    'user_id' => $maestros->count() > 1 ? $maestros->skip(1)->first()->id : $maestros->first()->id,
                    'sala_id' => $salasDisponibles->where('codigo', 'A101')->first()->id ?? $salasDisponibles->skip(1)->first()->id,
                    'dia_semana' => 'martes',
                    'hora_inicio' => '10:00',
                    'hora_fin' => '12:00',
                    'fecha_inicio' => '2025-01-15',
                    'fecha_fin' => '2025-12-15',
                    'observaciones' => 'Horario de matemáticas nivel 1'
                ]
            ];

            foreach ($horariosEjemplo as $horarioData) {
                // Usar updateOrCreate con una clave única compuesta para idempotencia
                $unique = [
                    'user_id' => $horarioData['user_id'],
                    'dia_semana' => $horarioData['dia_semana'],
                    'hora_inicio' => $horarioData['hora_inicio'],
                    'sala_id' => $horarioData['sala_id']
                ];

                Horario::updateOrCreate($unique, $horarioData);
                $this->command->info("Horario asegurado: {$horarioData['dia_semana']} {$horarioData['hora_inicio']}-{$horarioData['hora_fin']}");
            }

            // Crear algunas tareas de ejemplo
            if ($materiasDisponibles->count() > 0) {
                $tareasEjemplo = [
                    [
                        'titulo' => 'Ejercicios de Variables y Tipos de Datos',
                        'descripcion' => 'Resolver los ejercicios del capítulo 2 del libro de texto sobre variables y tipos de datos en programación.',
                        'materia_id' => $materiasDisponibles->where('materia', 'Programación I')->first()->id ?? $materiasDisponibles->first()->id,
                        'maestro_id' => $maestros->first()->id,
                        'grupo' => '1A',
                        'semestre' => '1',
                        'fecha_entrega' => now()->addDays(7),
                        'puntos_totales' => 100,
                        'tipo' => 'tarea',
                        'estado' => 'activa',
                        'instrucciones' => 'Entregar en formato PDF con código fuente comentado.'
                    ],
                    [
                        'titulo' => 'Proyecto Final - Sistema de Inventario',
                        'descripcion' => 'Desarrollar un sistema básico de inventario usando los conceptos vistos en clase.',
                        'materia_id' => $materiasDisponibles->where('materia', 'Programación I')->first()->id ?? $materiasDisponibles->first()->id,
                        'maestro_id' => $maestros->first()->id,
                        'grupo' => '1A',
                        'semestre' => '1',
                        'fecha_entrega' => now()->addDays(14),
                        'puntos_totales' => 200,
                        'tipo' => 'proyecto',
                        'estado' => 'activa',
                        'instrucciones' => 'Incluir documentación técnica y manual de usuario.'
                    ]
                ];

                foreach ($tareasEjemplo as $tareaData) {
                    Tarea::updateOrCreate(['titulo' => $tareaData['titulo']], $tareaData);
                    $this->command->info("Tarea asegurada: {$tareaData['titulo']}");
                }

                // Generar horario semanal completo: lunes-sabado, 3 franjas por día
                $dias = ['lunes','martes','miercoles','jueves','viernes','sabado'];
                $franjas = [
                    ['hora_inicio' => '08:00', 'hora_fin' => '10:00'],
                    ['hora_inicio' => '10:00', 'hora_fin' => '12:00'],
                    ['hora_inicio' => '13:00', 'hora_fin' => '15:00']
                ];

                $materiasArray = $materiasDisponibles->values()->all();
                $maestrosArray = $maestros->values()->all();
                $salasArray = $salasDisponibles->values()->all();

                $mIndex = 0; $uIndex = 0; $sIndex = 0;

                foreach ($dias as $dia) {
                    foreach ($franjas as $franja) {
                        // Rotación simple: tomar siguiente materia/maestro/sala
                        $materia = $materiasArray[$mIndex % count($materiasArray)];
                        $maestro = $maestrosArray[$uIndex % count($maestrosArray)];
                        $sala = $salasArray[$sIndex % count($salasArray)];

                        $horarioData = [
                            'materia_id' => $materia->id,
                            'user_id' => $maestro->id,
                            'sala_id' => $sala->id,
                            'dia_semana' => $dia,
                            'hora_inicio' => $franja['hora_inicio'],
                            'hora_fin' => $franja['hora_fin'],
                            'fecha_inicio' => '2025-01-15',
                            'fecha_fin' => '2025-12-15',
                            'observaciones' => "Horario automático: {$dia} {$franja['hora_inicio']}-{$franja['hora_fin']}"
                        ];

                        $unique = [
                            'user_id' => $horarioData['user_id'],
                            'dia_semana' => $horarioData['dia_semana'],
                            'hora_inicio' => $horarioData['hora_inicio'],
                            'sala_id' => $horarioData['sala_id']
                        ];

                        Horario::updateOrCreate($unique, $horarioData);

                        $mIndex++; $uIndex++; $sIndex++;
                    }
                }

                $this->command->info('Horario semanal completo asegurado (3 clases por día, lunes-sábado).');
            }
        }

        $this->command->info('Datos de prueba para Control Escolar creados exitosamente!');
        $this->command->info('');
        $this->command->info('Credenciales de maestros creados:');
        foreach ($maestros as $maestro) {
            $this->command->info("- {$maestro->name}: {$maestro->email} / password: maestro123");
        }
    }
}
