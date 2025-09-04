<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [[
            'email'=> 'admin@tecvalles.mx',
            'nombre' => 'admin',
            'password' => bcrypt ('12345678'),
            'tipo' => 1,
        ]];

        foreach($users as $user){
            User::create($user);
        }

        $this->command->info('Usuarios creados');
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
