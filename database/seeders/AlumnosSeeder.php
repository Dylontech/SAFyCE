<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Especialidade;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlumnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Desactivar verificaciones de claves foráneas para mejor performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Alumno::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $alumnos = [];
        // Obtener especialidades desde la tabla; si no hay, usar fallback
        $especialidades = Especialidade::pluck('nombre')->toArray();
        if (empty($especialidades)) {
            $especialidades = ['diseno grafico digital', 'ventas', 'produccion industrial de alimentos', 'NA'];
        }
        $estatusOptions = ['Activo', 'Inactivo'];
        
        // Grupos por semestre
        $gruposPorSemestre = [
            1 => ['124', '128', '129a', '129b', '129c'],
            2 => ['224', '228', '229a', '229b', '229c'],
            3 => ['324', '328', '329a', '329b', '329c'],
            4 => ['424', '428', '429a', '429b', '429c'],
            5 => ['524', '528', '529a', '529b', '529c'],
            6 => ['624', '628', '629a', '629b']
        ];

        // Nombres y apellidos para generar datos realistas
        $nombres = [
            'Juan', 'Maria', 'Carlos', 'Ana', 'Luis', 'Laura', 'Miguel', 'Elena', 
            'Jose', 'Carmen', 'Francisco', 'Isabel', 'Antonio', 'Patricia', 'Javier', 'Rosa',
            'Pedro', 'Lucia', 'Angel', 'Sofia', 'David', 'Paula', 'Daniel', 'Martina',
            'Jorge', 'Julia', 'Manuel', 'Claudia', 'Pablo', 'Valeria', 'Raul', 'Andrea',
            'Fernando', 'Alba', 'Diego', 'Noa', 'Sergio', 'Carla', 'Alberto', 'Vega',
            'Adrian', 'Lola', 'Ruben', 'Marta', 'Ivan', 'Chloe', 'Oscar', 'Aitana',
            'Roberto', 'Daniela', 'Mario', 'Candela', 'Alex', 'Lara', 'Hector', 'Sara',
            'Gonzalo', 'Nerea', 'Marcos', 'Ines', 'Julian', 'Jimena', 'Guillermo', 'Clara'
        ];

        $apellidos = [
            'Garcia', 'Rodriguez', 'Gonzalez', 'Fernandez', 'Lopez', 'Martinez', 'Sanchez', 'Perez',
            'Gomez', 'Martin', 'Jimenez', 'Ruiz', 'Hernandez', 'Diaz', 'Moreno', 'Alvarez',
            'Munoz', 'Romero', 'Alonso', 'Gutierrez', 'Navarro', 'Torres', 'Dominguez', 'Vazquez',
            'Ramos', 'Gil', 'Ramirez', 'Serrano', 'Blanco', 'Molina', 'Morales', 'Suarez',
            'Ortega', 'Delgado', 'Castro', 'Ortiz', 'Rubio', 'Marin', 'Sanz', 'Nunez',
            'Iglesias', 'Medina', 'Garrido', 'Cortes', 'Castillo', 'Santos', 'Lozano', 'Guerrero',
            'Cano', 'Prieto', 'Mendez', 'Cruz', 'Calvo', 'Vidal', 'Leon', 'Marquez',
            'Herrera', 'Pena', 'Flores', 'Cabrera', 'Campos', 'Vega', 'Fuentes', 'Reyes'
        ];

        $timestamp = Carbon::now();

        for ($i = 1; $i <= 1000; $i++) {
            $numeroControl = $this->generarNumeroControl($i);
            $nombreCompleto = $this->generarNombreCompleto($nombres, $apellidos);
            $curp = $this->generarCURP($nombreCompleto, $i);
            $email = $this->generarEmail($nombreCompleto, $i);
            $semestre = rand(1, 6);
            $grupo = $gruposPorSemestre[$semestre][array_rand($gruposPorSemestre[$semestre])];
            $especialidad = $especialidades[array_rand($especialidades)];
            $estatus = $estatusOptions[array_rand($estatusOptions)];

            $alumnos[] = [
                'numero_control' => $numeroControl,
                'CURP' => $curp,
                'especialidad' => $especialidad,
                'semestre' => $semestre,
                'Grupo' => $grupo,
                'Nombre' => $nombreCompleto,
                'email' => $email,
                'estatus' => $estatus,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            // Insertar en lotes de 100 para mejor performance
            if ($i % 100 === 0) {
                Alumno::insert($alumnos);
                $alumnos = [];
            }
        }

        // Insertar los registros restantes
        if (!empty($alumnos)) {
            Alumno::insert($alumnos);
        }

        $this->command->info('✅ 1000 alumnos generados exitosamente!');
    }

    /**
     * Genera un número de control único
     */
    private function generarNumeroControl($index)
    {
        // Formato: YY + 6 dígitos de secuencia = 8 caracteres
        // Usamos el índice mod 1_000_000 para evitar secuencias demasiado largas
        $year = date('y'); // Año actual en 2 dígitos
        $secuencia = str_pad($index % 1000000, 6, '0', STR_PAD_LEFT);
        return $year . $secuencia;
    }

    /**
     * Genera un nombre completo realista
     */
    private function generarNombreCompleto($nombres, $apellidos)
    {
        $nombre = $nombres[array_rand($nombres)];
        $segundoNombre = rand(0, 1) ? $nombres[array_rand($nombres)] . ' ' : '';
        $apellidoPaterno = $apellidos[array_rand($apellidos)];
        $apellidoMaterno = $apellidos[array_rand($apellidos)];
        
        return $apellidoPaterno . ' ' . $apellidoMaterno . ' ' . $nombre . ($segundoNombre ? ' ' . $segundoNombre : '');
    }

    /**
     * Genera una CURP ficticia pero con formato válido
     */
    private function generarCURP($nombreCompleto, $index)
    {
        $palabras = explode(' ', $nombreCompleto);
        $apellidoPaterno = $palabras[0];
        $apellidoMaterno = $palabras[1];
        $primerNombre = $palabras[2];
        
        // Primera letra del apellido paterno
        $letra1 = substr($apellidoPaterno, 0, 1);
        // Primera vocal interna del apellido paterno
        $vocal = $this->extraerPrimeraVocal(substr($apellidoPaterno, 1));
        // Primera letra del apellido materno
        $letra3 = substr($apellidoMaterno, 0, 1);
        // Primera letra del primer nombre
        $letra4 = substr($primerNombre, 0, 1);
        
        // Año (85-05 para simular edades entre 18-38 años)
        $year = str_pad(rand(85, 5), 2, '0', STR_PAD_LEFT);
        // Mes
        $mes = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        // Día
        $dia = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
        // Sexo (H o M)
        $sexo = rand(0, 1) ? 'H' : 'M';
        // Entidad federativa (2 letras)
        $entidades = ['AS', 'BC', 'BS', 'CC', 'CL', 'CM', 'CS', 'CH', 'DF', 'DG', 
                     'GT', 'GR', 'HG', 'JC', 'MC', 'MN', 'MS', 'NT', 'NL', 'OC', 
                     'PL', 'QT', 'QR', 'SP', 'SL', 'SR', 'TC', 'TL', 'TS', 'VZ', 
                     'YN', 'ZS'];
        $entidad = $entidades[array_rand($entidades)];
        // Consonantes internas
        $consonante1 = $this->extraerPrimeraConsonante(substr($apellidoPaterno, 1));
        $consonante2 = $this->extraerPrimeraConsonante(substr($apellidoMaterno, 1));
        $consonante3 = $this->extraerPrimeraConsonante(substr($primerNombre, 1));
        // Dígito verificador
        $verificador = str_pad(rand(0, 9), 1, '0', STR_PAD_LEFT);

        return strtoupper(
            $letra1 . $vocal . $letra3 . $letra4 . 
            $year . $mes . $dia . $sexo . $entidad . 
            $consonante1 . $consonante2 . $consonante3 . $verificador
        );
    }

    /**
     * Extrae la primera vocal de una cadena
     */
    private function extraerPrimeraVocal($cadena)
    {
        $vocales = ['A', 'E', 'I', 'O', 'U'];
        for ($i = 0; $i < strlen($cadena); $i++) {
            $letra = strtoupper($cadena[$i]);
            if (in_array($letra, $vocales)) {
                return $letra;
            }
        }
        return 'X';
    }

    /**
     * Extrae la primera consonante de una cadena
     */
    private function extraerPrimeraConsonante($cadena)
    {
        $vocales = ['A', 'E', 'I', 'O', 'U'];
        for ($i = 0; $i < strlen($cadena); $i++) {
            $letra = strtoupper($cadena[$i]);
            if (!in_array($letra, $vocales) && ctype_alpha($letra)) {
                return $letra;
            }
        }
        return 'X';
    }

    /**
     * Genera un email único basado en el nombre (CORREGIDO)
     */
    private function generarEmail($nombreCompleto, $index)
    {
        $dominios = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'estudiantes.edu.mx'];
        
        // Limpiar y formatear el nombre para el email (sin caracteres especiales)
        $palabras = explode(' ', $nombreCompleto);
        $primerNombre = strtolower($this->quitarAcentos($palabras[2]));
        $apellidoPaterno = strtolower($this->quitarAcentos($palabras[0]));
        
        // Solo usar letras y números en el email
        $primerNombre = preg_replace('/[^a-z0-9]/', '', $primerNombre);
        $apellidoPaterno = preg_replace('/[^a-z0-9]/', '', $apellidoPaterno);
        
        // Tomar solo los primeros 3-5 caracteres para evitar emails muy largos
        $primerNombreCorto = substr($primerNombre, 0, 5);
        $apellidoCorto = substr($apellidoPaterno, 0, 3);
        
        // Variaciones de formato de email (más simples)
        $formatos = [
            $primerNombreCorto . '.' . $apellidoCorto . $index,
            $primerNombreCorto . $apellidoCorto . $index,
            $apellidoCorto . '.' . $primerNombreCorto . $index,
            $primerNombreCorto . rand(100, 999),
            $apellidoCorto . rand(100, 999)
        ];
        
        $usuario = $formatos[array_rand($formatos)];
        $dominio = $dominios[array_rand($dominios)];
        
        return $usuario . '@' . $dominio;
    }

    /**
     * Quita acentos de una cadena
     */
    private function quitarAcentos($cadena)
    {
        $acentos = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        ];
        return strtr($cadena, $acentos);
    }
}