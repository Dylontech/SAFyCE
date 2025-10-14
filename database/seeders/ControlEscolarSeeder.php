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
            // Crear algunos horarios de ejemplo
            $horariosEjemplo = [
                [
                    'materia_id' => $materiasDisponibles->where('materia', 'Programación I')->first()->id ?? $materiasDisponibles->first()->id,
                    'maestro_id' => $maestros->first()->id,
                    'sala_id' => $salasDisponibles->where('codigo', 'LAB-COMP')->first()->id ?? $salasDisponibles->first()->id,
                    'grupo' => '1A',
                    'dia_semana' => 'lunes',
                    'hora_inicio' => '08:00',
                    'hora_fin' => '10:00',
                    'semestre' => '1',
                    'periodo_escolar' => '2024-2025-1',
                    'estado' => 'activo'
                ],
                'user_id' => $maestros->first()->id,
                    'sala_id' => $salasDisponibles->where('codigo', 'LAB001')->first()->id ?? $salasDisponibles->first()->id,
                    'dia_semana' => 'lunes',
                    'hora_inicio' => '08:00',
                    'hora_fin' => '10:00',
                    'fecha_inicio' => '2025-01-15',
                    'fecha_fin' => '2025-12-15',
                    'observaciones' => 'Horario de programación básica'
                ],
                [
                    'materia_id' => $materiasDisponibles->where('materia', 'Matemáticas I')->first()->id ?? $materiasDisponibles->skip(1)->first()->id,
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
                if (!Horario::where('dia_semana', $horarioData['dia_semana'])
                           ->where('hora_inicio', $horarioData['hora_inicio'])
                           ->where('sala_id', $horarioData['sala_id'])
                           ->exists()) {
                    Horario::create($horarioData);
                    $this->command->info("Horario creado: {$horarioData['dia_semana']} {$horarioData['hora_inicio']}-{$horarioData['hora_fin']}");
                }
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
                    if (!Tarea::where('titulo', $tareaData['titulo'])->exists()) {
                        Tarea::create($tareaData);
                        $this->command->info("Tarea creada: {$tareaData['titulo']}");
                    }
                }
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
