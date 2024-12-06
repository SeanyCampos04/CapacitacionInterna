<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nombre' => 'admin'],
            ['nombre' => 'Jefe departamento'],
            ['nombre' => 'Subdirector Academico'],
            ['nombre' => 'CAD'],
            ['nombre' => 'Instructor']
        ];

        foreach($roles as $role){
            role::create($role);
        }

        $this->command->info('Roles creados');
    }
}
