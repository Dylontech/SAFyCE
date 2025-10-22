<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriasSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('⏳ Sembrando materias comunes...');

        // Materias comunes por semestre (ejemplo general)
        $materiasPorSemestre = [
            1 => [
                'Matematicas I', 'Introduccion a la Informatica', 'Comunicacion Oral y Escrita', 'Taller de Etica'
            ],
            2 => [
                'Matematicas II', 'Programacion I', 'Taller de Lectura', 'Quimica'
            ],
            3 => [
                'Matematicas III', 'Programacion II', 'Fisica I', 'Fundamentos de Economia'
            ],
            4 => [
                'Estructuras de Datos', 'Bases de Datos', 'Electiva Profesional I', 'Administracion'
            ],
            5 => [
                'Sistemas Operativos', 'Redes de Computadoras', 'Electiva Profesional II', 'Calidad'
            ],
            6 => [
                'Desarrollo de Proyectos', 'Seguridad Informatica', 'Emprendimiento', 'Practicas Profesionales'
            ]
        ];

        // Mapear nombres a un formato simple sin acentos si es necesario
        foreach ($materiasPorSemestre as $semestre => $materias) {
            foreach ($materias as $materiaNombre) {
                Materia::updateOrCreate(
                    [
                        'materia' => $materiaNombre,
                        'semestre' => $semestre
                    ],
                    [
                        'especialidad' => 'NA'
                    ]
                );
            }
        }

        // Además, añadir algunas materias específicas por especialidad
        $especificas = [
            'diseno grafico digital' => [
                ['materia' => 'Dibujo Digital', 'semestre' => 2],
                ['materia' => 'Tipografia', 'semestre' => 3],
                ['materia' => 'Composicion Visual', 'semestre' => 4]
            ],
            'produccion industrial de alimentos' => [
                ['materia' => 'Procesos de Alimentos', 'semestre' => 2],
                ['materia' => 'Tecnologia de Conservacion', 'semestre' => 4]
            ],
            'ventas' => [
                ['materia' => 'Tecnicas de Venta', 'semestre' => 1],
                ['materia' => 'Mercadotecnia', 'semestre' => 3]
            ],
            'informatica' => [
                ['materia' => 'Algoritmos y Programacion', 'semestre' => 1],
                ['materia' => 'Ingenieria de Software', 'semestre' => 4]
            ]
        ];

        foreach ($especificas as $esp => $lista) {
            foreach ($lista as $m) {
                Materia::updateOrCreate([
                    'materia' => $m['materia'],
                    'semestre' => $m['semestre']
                ], [
                    'especialidad' => $esp
                ]);
            }
        }

        $this->command->info('✔️ Materias sembradas.');
    }
}
