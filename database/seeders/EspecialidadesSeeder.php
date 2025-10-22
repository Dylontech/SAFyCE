<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Especialidade;
use Illuminate\Support\Facades\DB;

class EspecialidadesSeeder extends Seeder
{
    public function run()
    {
        // Lista de especialidades comunes
        $especialidades = [
            ['nombre' => 'diseno grafico digital', 'descripcion' => 'Diseño gráfico aplicado a medios digitales'],
            ['nombre' => 'ventas', 'descripcion' => 'Técnicas y procesos de ventas'],
            ['nombre' => 'produccion industrial de alimentos', 'descripcion' => 'Procesos industriales en alimentos'],
            ['nombre' => 'mantenimiento', 'descripcion' => 'Mantenimiento industrial y correctivo'],
            ['nombre' => 'informatica', 'descripcion' => 'Sistemas, redes y programación'],
            ['nombre' => 'administracion', 'descripcion' => 'Administración y gestión empresarial'],
            ['nombre' => 'NA', 'descripcion' => 'No aplica / General']
        ];

        foreach ($especialidades as $esp) {
            // Usar updateOrCreate para poder re-ejecutar el seeder sin duplicados
            Especialidade::updateOrCreate(
                ['nombre' => $esp['nombre']],
                ['descripcion' => $esp['descripcion']]
            );
        }

        $this->command->info('✔️ Especialidades sembradas.');
    }
}
