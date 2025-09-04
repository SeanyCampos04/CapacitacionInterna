<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartamentoSeeder::class,
            RoleSeeder::class,
            userSeeder::class,
            CursoSeeder::class,
            InitialSeeder::class,
            PeriodoSeeder::class,
            SugeridoSeeder::class,
            User_RoleSeeder::class,
            // Agrega aquí otros seeders, por ejemplo:
            // UserSeeder::class,
            // RoleSeeder::class,
        ]);
    }
}
