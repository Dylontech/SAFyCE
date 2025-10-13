<?php

namespace Database\Factories;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition()
    {
        $nombre = $this->faker->firstName;
        $apellido = $this->faker->lastName;
        $anioMes = now()->format('y') . now()->format('m');
        $matricula = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1) . $anioMes);

        return [
            'Matricula' => $matricula,
            'CURP' => strtoupper($this->faker->bothify('????######????????##')),
            'Carrera' => $this->faker->randomElement(['Ingeniería', 'Arquitectura', 'Medicina', 'Derecho']),
            'Grupo' => $this->faker->randomElement(['124', '128', '129a', '129b', '129c', '224', '228', '229a', '229b', '229c', '324', '328', '329a', '329b', '329c', '424', '428', '429a', '429b', '429c', '524', '528', '529a', '529b', '529c', '624', '628', '629a', '629b']),
            'Nombre' => $nombre . ' ' . $apellido,
            'email' => $this->faker->unique()->safeEmail,
            'estatus' => $this->faker->randomElement(['activo', 'inactivo']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
