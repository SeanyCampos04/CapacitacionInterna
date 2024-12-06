<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Periodo;
class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = [
            ['periodo'=> 'AGOSTO-DICIEMBRE 2023'],
            ['periodo'=> 'ENERO-JULIO 2024'],
            ['periodo'=> 'AGOSTO-DICIEMBRE 2024']
        ];

        foreach($periodos as $periodo){
            Periodo::create($periodo);
        }

        $this->command->info('Periodos creados');
    }
}
