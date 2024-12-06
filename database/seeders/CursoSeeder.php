<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = [
            ['nombre' => 'Curso de fifa', 'instructor' => 'Osito', 'departamento' => 'Sistema y computacion',
                'periodo' => 'AGOSTO-DICIEMBRE 2023', 'duracion' => '30', 'horario' => '8:00-11:00', 'folio' => '001',
                    'modalidad' => 'Presencial', 'sede' => 'Tecnologico', 'limiteAlumnos' => '25'],

            ['nombre' => 'Cursos de programacion', 'instructor' => 'Cesar', 'departamento' => 'Sistema y computacion',
                'periodo' => 'ENERO-JULIO 2024', 'duracion' => '52', 'horario' => '15:00-18:00', 'folio' => '002',
                    'modalidad' => 'Mixta', 'sede' => 'Tecnologico', 'limiteAlumnos' => '15'],

            ['nombre' => 'Adiminstracion Financiera', 'instructor' => 'Neto', 'departamento' => 'Ciencias Economico',
                'periodo' => 'ENERO-JULIO 2024', 'duracion' => '24', 'horario' => '12:00-16:00', 'folio' => '003',
                    'modalidad' => 'Presencial', 'sede' => 'Tecnologico', 'limiteAlumnos' => '25'],

            ['nombre' => 'Curso de Arduino UNO', 'instructor' => 'Zinchy', 'departamento' => 'Sistema y computacion',
                'periodo' => 'AGOSTO-DICIEMBRE 2023', 'duracion' => '30', 'horario' => '11:00-13:00', 'folio' => '004',
                    'modalidad' => 'Presencial', 'sede' => 'Tecnologico', 'limiteAlumnos' => '30'],

            ['nombre' => 'Curso de Laravel', 'instructor' => 'Neto', 'departamento' => 'Sistema y computacion',
                'periodo' => 'ENERO-JULIO 2024', 'duracion' => '30', 'horario' => '8:00-11:00', 'folio' => '005',
                    'modalidad' => 'Mixta', 'sede' => 'Tecnologico', 'limiteAlumnos' => '20'],

            ['nombre' => 'Curso de GitHub', 'instructor' => 'Esaú', 'departamento' => 'Sistema y computacion',
                'periodo' => 'ENERO-JULIO 2024', 'duracion' => '30', 'horario' => '9:00-12:00', 'folio' => '006',
                    'modalidad' => 'Mixta', 'sede' => 'Tecnologico', 'limiteAlumnos' => '25'],

            ['nombre' => 'Hacking Avanzado', 'instructor' => 'Lukas', 'departamento' => 'Sistema y computacion',
                'periodo' => 'AGOSTO-DICIEMBRE 2023', 'duracion' => '30', 'horario' => '12:00-15:00', 'folio' => '007',
                    'modalidad' => 'Mixta', 'sede' => 'Tecnologico', 'limiteAlumnos' => '30'],

            ['nombre' => 'Office Deluxe', 'instructor' => 'Camacho', 'departamento' => 'Sistema y computacion',
                'periodo' => 'ENERO-JULIO 2024', 'duracion' => '30', 'horario' => '11:00-14:00', 'folio' => '008',
                    'modalidad' => 'Presencial', 'sede' => 'Tecnologico', 'limiteAlumnos' => '30'],

            ['nombre' => 'Futuros Agrónomos', 'instructor' => 'Rafael', 'departamento' => 'Agronomia',
                'periodo' => 'ENERO-JULIO 2024', 'duracion' => '30', 'horario' => '7:00-10:00', 'folio' => '009',
                    'modalidad' => 'Presencial', 'sede' => 'Tecnologico', 'limiteAlumnos' => '25'],

            ['nombre' => 'Industrias Manufactureras', 'instructor' => 'Eduardo', 'departamento' => 'Industrial',
                'periodo' => 'AGOSTO-DICIEMBRE 2023', 'duracion' => '30', 'horario' => '8:00-11:00', 'folio' => '010',
                    'modalidad' => 'Presencial', 'sede' => 'Tecnologico', 'limiteAlumnos' => '30'],

        ];

        foreach($cursos as $curso){
            Curso::create($curso);
        }

        $this->command->info('Cursos creados');
    }
}
/*DEPARTAMENTOS
Ciencias Basicas
Ciencias Economico - Administrativas
Sistema y computacion
Industrial
Ingenierias
Agronomia
*/
/*MODALIDAD
Presencial
Mixta
Linea
*/
