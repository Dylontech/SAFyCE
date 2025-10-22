<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar verificaciones de claves foráneas para mejor performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Grupo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $grupos = [];
        
        // Definir las letras de grupo estándar
        $letrasGrupo = ['a', 'b', 'c'];
        
        // Para carreras técnicas (semestres 1-6)
        for ($semestre = 1; $semestre <= 6; $semestre++) {
            foreach ($letrasGrupo as $letra) {
                $grupos[] = [
                    'semestre' => $semestre,
                    'letra' => $letra,
                    'nombre_completo' => $semestre . $letra,
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            // Agregar grupos especiales para carreras específicas
            if (in_array($semestre, [1, 2, 3, 4, 5])) {
                // Grupos adicionales para especialidades específicas
                $gruposEspeciales = ['24', '28', '29a', '29b', '29c'];
                foreach ($gruposEspeciales as $grupoEspecial) {
                    $grupos[] = [
                        'semestre' => $semestre,
                        'letra' => $grupoEspecial,
                        'nombre_completo' => $semestre . $grupoEspecial,
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        // Para licenciaturas o carreras universitarias (semestres 7-12)
        for ($semestre = 7; $semestre <= 12; $semestre++) {
            // Solo grupos básicos para semestres avanzados
            foreach (['a', 'b'] as $letra) {
                $grupos[] = [
                    'semestre' => $semestre,
                    'letra' => $letra,
                    'nombre_completo' => $semestre . $letra,
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Algunos grupos inactivos de ejemplo
        $gruposInactivos = [
            ['semestre' => 1, 'letra' => 'd', 'nombre_completo' => '1d', 'activo' => false],
            ['semestre' => 2, 'letra' => 'd', 'nombre_completo' => '2d', 'activo' => false],
            ['semestre' => 6, 'letra' => 'd', 'nombre_completo' => '6d', 'activo' => false],
        ];

        foreach ($gruposInactivos as $grupo) {
            $grupos[] = array_merge($grupo, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Insertar en lotes para mejor performance
        $chunks = array_chunk($grupos, 50);
        foreach ($chunks as $chunk) {
            Grupo::insert($chunk);
        }

        $this->command->info('Se han creado ' . count($grupos) . ' grupos exitosamente.');
        $this->command->info('Grupos activos: ' . collect($grupos)->where('activo', true)->count());
        $this->command->info('Grupos inactivos: ' . collect($grupos)->where('activo', false)->count());
    }
}
