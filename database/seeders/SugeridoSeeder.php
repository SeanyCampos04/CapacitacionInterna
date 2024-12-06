<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SolicitarCurso;
class SugeridoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sugeridos = [
            ['user_id'=> '2', 'nombre' => 'curso de fifa',
                'departamento' => 'Sistema y computacion', 'objetivo' => 'desarrollar....',
                    'instructor' => 'oso', 'participantes' => '30', 'prioridad' => 'Baja',
                        'origen' => 'evaluacion docente', 'requerimientos' => 'tener fifa'],
        ];

        foreach($sugeridos as $sugerido){
            SolicitarCurso::create($sugerido);
        }

        $this->command->info('Solicitaciones de curso creadas');
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
/*PRIORIDAD
Alta
Media
Baja
*/
