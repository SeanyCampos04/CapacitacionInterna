<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['email'=> 'admin@tecvalles.mx', 'name' => 'admin', 'password' => '12345678'],

        ];

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
