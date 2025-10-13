<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use Faker\Factory as Faker;

class AlumnosTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_US'); // Usamos inglés para evitar caracteres especiales

        // Especialidades sin caracteres especiales
        $especialidades = [
            'diseno grafico digital',
            'ventas', 
            'produccion industrial de alimentos',
            'NA'
        ];

        // Grupos por semestre
        $gruposPorSemestre = [
            1 => ['124', '128', '129a', '129b', '129c'],
            2 => ['224', '228', '229a', '229b', '229c'],
            3 => ['324', '328', '329a', '329b', '329c'],
            4 => ['424', '428', '429a', '429b', '429c'],
            5 => ['524', '528', '529a', '529b', '529c'],
            6 => ['624', '628', '629a', '629b']
        ];

        // Estatus
        $estatus = ['Activo', 'Inactivo'];

        for ($i = 1; $i <= 500; $i++) {
            $genero = $faker->randomElement(['male', 'female']);
            $nombre = $this->removeAccents($faker->firstName($genero));
            $apellidoPaterno = $this->removeAccents($faker->lastName);
            $apellidoMaterno = $this->removeAccents($faker->lastName);
            $semestre = $faker->numberBetween(1, 6);

            Alumno::create([
                'numero_control' => 'CTRL' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'CURP' => $this->generateSimpleCURP($apellidoPaterno, $apellidoMaterno, $nombre, $faker->dateTimeBetween('-25 years', '-17 years'), $genero),
                'especialidad' => $faker->randomElement($especialidades),
                'semestre' => (string)$semestre,
                'Grupo' => $faker->randomElement($gruposPorSemestre[$semestre]),
                'Nombre' => "$nombre $apellidoPaterno $apellidoMaterno",
                'email' => strtolower(substr($nombre, 0, 1) . $apellidoPaterno . $i . '@tecnm.mx'),
                'estatus' => $faker->randomElement($estatus),
            ]);
        }
    }

    /**
     * Elimina todos los acentos y caracteres especiales
     */
    private function removeAccents($string)
    {
        $string = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ', 'Ü', 'ü'],
            ['A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'N', 'n', 'U', 'u'],
            $string
        );
        
        // Eliminar cualquier otro carácter especial
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    /**
     * Genera una CURP simple sin caracteres especiales
     */
    private function generateSimpleCURP($apellido1, $apellido2, $nombre, $fechaNacimiento, $genero)
    {
        $apellido1 = $this->removeAccents($apellido1);
        $apellido2 = $this->removeAccents($apellido2);
        $nombre = $this->removeAccents($nombre);

        $letra1 = substr($apellido1, 0, 1);
        $vocal = preg_replace('/[^AEIOU]/i', '', substr($apellido1, 1));
        $vocal1 = substr($vocal, 0, 1) ?: 'X';
        
        $letra2 = substr($apellido2, 0, 1) ?: 'X';
        $letraNombre = substr($nombre, 0, 1);
        
        $fecha = $fechaNacimiento->format('ymd');
        
        $sexo = $genero == 'male' ? 'H' : 'M';
        $estado = 'DF'; // Código para Ciudad de México
        
        $consonantes = preg_replace('/[AEIOU]/i', '', substr($apellido1, 1) . substr($apellido2, 1) . substr($nombre, 1));
        $consonante1 = substr($consonantes, 0, 1) ?: 'X';
        $consonante2 = substr($consonantes, 1, 1) ?: 'X';
        
        $digitos = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        
        return strtoupper(
            $letra1 . $vocal1 . $letra2 . $letraNombre . 
            $fecha . $sexo . $estado . 
            $consonante1 . $consonante2 . $digitos
        );
    }
}