<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            ['nombre' => 'Ciencias Basicas'],
            ['nombre' => 'Ciencias Economico - Administrativas'],
            ['nombre' => 'Sistema y computacion'],
            ['nombre' => 'Industrial'],
            ['nombre' => 'Ingenierias'],
            ['nombre' => 'Agronomia']
        ];

        foreach($departamentos as $departamento){
            Departamento::create($departamento);
        }

        $this->command->info('Departamentos creados');
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
/*ROLES
admin
jefedepartamento
docente
*/
