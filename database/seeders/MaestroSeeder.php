<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MaestroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios maestros de ejemplo
        $maestros = [
            [
                'name' => 'Prof. Juan Carlos Pérez',
                'email' => 'juan.perez@ejemplo.edu.mx',
                'password' => Hash::make('maestro123'),
                'especialidad' => 'Programación',
                'numero_empleado' => 'EMP001'
            ],
            [
                'name' => 'Prof. María Elena González',
                'email' => 'maria.gonzalez@ejemplo.edu.mx',
                'password' => Hash::make('maestro123'),
                'especialidad' => 'Matemáticas',
                'numero_empleado' => 'EMP002'
            ],
            [
                'name' => 'Prof. Roberto Martínez',
                'email' => 'roberto.martinez@ejemplo.edu.mx',
                'password' => Hash::make('maestro123'),
                'especialidad' => 'Física',
                'numero_empleado' => 'EMP003'
            ],
            [
                'name' => 'Prof. Ana Lucía Hernández',
                'email' => 'ana.hernandez@ejemplo.edu.mx',
                'password' => Hash::make('maestro123'),
                'especialidad' => 'Química',
                'numero_empleado' => 'EMP004'
            ]
        ];

        foreach ($maestros as $maestroData) {
            // Verificar si el usuario ya existe
            $maestro = User::where('email', $maestroData['email'])->first();
            
            if (!$maestro) {
                $maestro = User::create([
                    'name' => $maestroData['name'],
                    'email' => $maestroData['email'],
                    'password' => $maestroData['password'],
                    'email_verified_at' => now(),
                ]);

                // Asignar el rol de maestro
                $maestro->assignRole('maestro');

                $this->command->info("Maestro creado: {$maestroData['name']} - {$maestroData['email']}");
            } else {
                $this->command->info("Maestro ya existe: {$maestroData['name']} - {$maestroData['email']}");
            }
        }

        $this->command->info("Seeders de maestros completados. Credenciales: email del maestro / password: maestro123");
    }
}
