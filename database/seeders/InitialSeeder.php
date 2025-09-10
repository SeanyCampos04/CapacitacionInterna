<?php

namespace Database\Seeders;

use App\Models\Datos_generale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;
use App\Models\user_role;
use App\Models\Departamento;
use App\Models\Instructore;
use App\Models\Participante;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            ['email'=> 'admin@tecvalles.mx', 'password' => bcrypt('12345678'), 'tipo' => '1']
        ];

        foreach($users as $user){
            User::create($user);
        }

        $this->command->info('Usuarios creados');

        $roles = [
            ['nombre' => 'admin'],
            ['nombre' => 'Jefe Departamento'],
            ['nombre' => 'Subdirector Academico'],
            ['nombre' => 'CAD'],
            ['nombre' => 'Instructor']
        ];

        foreach($roles as $role){
            role::create($role);
        }

        $this->command->info('Roles creados');

        $user_roles = [
            ['user_id'=> '1','role_id' => '1'],
        ];

        foreach($user_roles as $user_role){
            user_role::create($user_role);
        }

        $this->command->info('Usuarios con roles creados');

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

        $datos_generales = [
            ['user_id' => '1', 'nombre' => 'admin', 'departamento_id' => '1']
        ];

        foreach($datos_generales as $datos){
            Datos_generale::create($datos);
        }

        $this->command->info('Datos generales creados');

        $participantes = [
            ['user_id' => '1', 'plantel' => '.', 'horas' => '0', 'puesto' => 'admin']
        ];

        foreach($participantes as $participante){
            Participante::create($participante);
        }

        $this->command->info('Participantes creados');

    }
}
