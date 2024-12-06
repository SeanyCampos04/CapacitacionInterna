<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user_role;

class User_RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_roles = [
            ['user_id'=> '1','role_id' => '1'],
        ];

        foreach($user_roles as $user_role){
            user_role::create($user_role);
        }

        $this->command->info('Usuarios con roles creados');
    }
}
