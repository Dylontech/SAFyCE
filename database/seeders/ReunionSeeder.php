<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reunion;
use App\Models\User;
use App\Models\Sala;
use Carbon\Carbon;

class ReunionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener un usuario administrador para asignar como creador
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::first();
        }

        // Obtener algunas salas
        $salas = Sala::limit(3)->get();

        $reuniones = [
            [
                'titulo' => 'Clase de Matemáticas - Álgebra Lineal',
                'descripcion' => 'Clase regular de matemáticas, tema: sistemas de ecuaciones lineales',
                'fecha' => Carbon::today(),
                'hora' => '09:00:00',
                'duracion' => 90,
                'plataforma' => 'meet',
                'tipo' => 'clase',
                'sala_id' => $salas->isNotEmpty() ? $salas->first()->id : null,
                'user_id' => $admin->id,
                'codigo_reunion' => 'mat-alg-lin-001',
                'max_participantes' => 30
            ],
            [
                'titulo' => 'Tutoría Grupal - Física',
                'descripcion' => 'Sesión de tutoría para resolver dudas sobre mecánica clásica',
                'fecha' => Carbon::today(),
                'hora' => '14:30:00',
                'duracion' => 60,
                'plataforma' => 'meet',
                'tipo' => 'tutorial',
                'sala_id' => $salas->count() > 1 ? $salas->get(1)->id : null,
                'user_id' => $admin->id,
                'codigo_reunion' => 'tut-fis-mec-001',
                'max_participantes' => 20
            ],
            [
                'titulo' => 'Reunión de Padres de Familia',
                'descripcion' => 'Reunión informativa sobre el avance académico del segundo parcial',
                'fecha' => Carbon::tomorrow(),
                'hora' => '18:00:00',
                'duracion' => 120,
                'plataforma' => 'meet',
                'tipo' => 'reunion',
                'sala_id' => null, // Reunión virtual sin sala física
                'user_id' => $admin->id,
                'codigo_reunion' => 'reunion-padres-002',
                'max_participantes' => 100
            ],
            [
                'titulo' => 'Examen en Línea - Química Orgánica',
                'descripcion' => 'Examen parcial del segundo bloque de química orgánica',
                'fecha' => Carbon::today()->addDays(2),
                'hora' => '10:00:00',
                'duracion' => 120,
                'plataforma' => 'meet',
                'tipo' => 'examen',
                'sala_id' => $salas->count() > 2 ? $salas->get(2)->id : null,
                'user_id' => $admin->id,
                'codigo_reunion' => 'exam-quim-org-001',
                'max_participantes' => 25
            ],
            [
                'titulo' => 'Conferencia: Innovaciones Tecnológicas',
                'descripcion' => 'Conferencia magistral sobre las últimas innovaciones en tecnología aplicada',
                'fecha' => Carbon::today()->addDays(3),
                'hora' => '16:00:00',
                'duracion' => 90,
                'plataforma' => 'zoom',
                'tipo' => 'otro',
                'sala_id' => null,
                'user_id' => $admin->id,
                'codigo_reunion' => '1234567890',
                'max_participantes' => 200
            ],
            [
                'titulo' => 'Clase de Programación - Python Básico',
                'descripcion' => 'Introducción a la programación con Python: variables y estructuras de control',
                'fecha' => Carbon::today()->addDays(1),
                'hora' => '11:00:00',
                'duracion' => 120,
                'plataforma' => 'teams',
                'tipo' => 'clase',
                'sala_id' => $salas->isNotEmpty() ? $salas->first()->id : null,
                'user_id' => $admin->id,
                'codigo_reunion' => 'prog-python-bas-001',
                'max_participantes' => 35
            ]
        ];

        foreach ($reuniones as $reunionData) {
            $reunion = Reunion::create($reunionData);
            
            // Generar enlace según la plataforma
            $reunion->enlace_reunion = $reunion->generarEnlaceReunion();
            $reunion->save();
        }

        $this->command->info('Se han creado ' . count($reuniones) . ' reuniones de ejemplo.');
    }
}
