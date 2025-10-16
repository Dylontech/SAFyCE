<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BibliotecaVirtual;

class BibliotecaVirtualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recursos = [
            // Bases de Datos Médicas y Científicas
            [
                'nombre' => 'eLibro',
                'url' => 'https://elibro.net',
                'descripcion' => 'Biblioteca digital con más de 200,000 títulos en español. Incluye libros académicos, científicos y de investigación.',
                'icono' => 'fas fa-book',
                'categoria' => 'libros',
                'orden' => 1,
                'activo' => true
            ],
            [
                'nombre' => 'EBSCOhost',
                'url' => 'https://search.ebscohost.com',
                'descripcion' => 'Plataforma de investigación que proporciona acceso a bases de datos bibliográficas y de texto completo.',
                'icono' => 'fas fa-database',
                'categoria' => 'bases_datos',
                'orden' => 2,
                'activo' => true
            ],
            [
                'nombre' => 'DynaMed',
                'url' => 'https://www.dynamed.com',
                'descripcion' => 'Base de datos médica con información clínica basada en evidencia para profesionales de la salud.',
                'icono' => 'fas fa-stethoscope',
                'categoria' => 'medicina',
                'orden' => 3,
                'activo' => true
            ],
            [
                'nombre' => 'SciELO',
                'url' => 'https://www.scielo.org',
                'descripcion' => 'Biblioteca electrónica que incluye una colección de revistas científicas de América Latina y el Caribe.',
                'icono' => 'fas fa-microscope',
                'categoria' => 'revistas',
                'orden' => 4,
                'activo' => true
            ],
            [
                'nombre' => 'PubMed',
                'url' => 'https://pubmed.ncbi.nlm.nih.gov',
                'descripcion' => 'Base de datos de literatura biomédica y de ciencias de la vida de la Biblioteca Nacional de Medicina.',
                'icono' => 'fas fa-pills',
                'categoria' => 'medicina',
                'orden' => 5,
                'activo' => true
            ],
            [
                'nombre' => 'DOAJ',
                'url' => 'https://doaj.org',
                'descripcion' => 'Directorio de revistas de acceso abierto que cubre todas las áreas del conocimiento.',
                'icono' => 'fas fa-unlock-alt',
                'categoria' => 'revistas',
                'orden' => 6,
                'activo' => true
            ],
            [
                'nombre' => 'REDALYC',
                'url' => 'https://www.redalyc.org',
                'descripcion' => 'Red de revistas científicas de América Latina y el Caribe, España y Portugal.',
                'icono' => 'fas fa-globe-americas',
                'categoria' => 'revistas',
                'orden' => 7,
                'activo' => true
            ],
            [
                'nombre' => 'Cochrane Library',
                'url' => 'https://www.cochranelibrary.com',
                'descripcion' => 'Colección de bases de datos de medicina basada en evidencia de alta calidad.',
                'icono' => 'fas fa-heart',
                'categoria' => 'medicina',
                'orden' => 8,
                'activo' => true
            ],
            [
                'nombre' => 'IEEE Xplore',
                'url' => 'https://ieeexplore.ieee.org',
                'descripcion' => 'Biblioteca digital que proporciona acceso a literatura técnica en ingeniería eléctrica y ciencias de la computación.',
                'icono' => 'fas fa-microchip',
                'categoria' => 'bases_datos',
                'orden' => 9,
                'activo' => true
            ],
            [
                'nombre' => 'ResearchGate',
                'url' => 'https://www.researchgate.net',
                'descripcion' => 'Red social para científicos e investigadores para compartir papers, hacer preguntas y colaborar.',
                'icono' => 'fas fa-users',
                'categoria' => 'general',
                'orden' => 10,
                'activo' => true
            ],
            [
                'nombre' => 'Google Scholar',
                'url' => 'https://scholar.google.com',
                'descripcion' => 'Buscador de literatura académica que incluye artículos, tesis, libros y resúmenes.',
                'icono' => 'fab fa-google',
                'categoria' => 'general',
                'orden' => 11,
                'activo' => true
            ],
            [
                'nombre' => 'JSTOR',
                'url' => 'https://www.jstor.org',
                'descripcion' => 'Biblioteca digital académica con artículos de revistas, libros y fuentes primarias.',
                'icono' => 'fas fa-archive',
                'categoria' => 'bases_datos',
                'orden' => 12,
                'activo' => true
            ],
            [
                'nombre' => 'SpringerLink',
                'url' => 'https://link.springer.com',
                'descripcion' => 'Plataforma de contenido científico, técnico y médico de Springer Nature.',
                'icono' => 'fas fa-leaf',
                'categoria' => 'bases_datos',
                'orden' => 13,
                'activo' => true
            ],
            [
                'nombre' => 'Wiley Online Library',
                'url' => 'https://onlinelibrary.wiley.com',
                'descripcion' => 'Biblioteca en línea que alberga artículos de revistas, libros y bases de datos de referencia.',
                'icono' => 'fas fa-graduation-cap',
                'categoria' => 'libros',
                'orden' => 14,
                'activo' => true
            ],
            [
                'nombre' => 'ScienceDirect',
                'url' => 'https://www.sciencedirect.com',
                'descripcion' => 'Plataforma digital de Elsevier con artículos científicos, libros y capítulos de libros.',
                'icono' => 'fas fa-flask',
                'categoria' => 'bases_datos',
                'orden' => 15,
                'activo' => true
            ]
        ];

        foreach ($recursos as $recurso) {
            BibliotecaVirtual::create($recurso);
        }
    }
}
