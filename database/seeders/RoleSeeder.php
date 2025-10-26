<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrateur système'],
            ['name' => 'agent', 'description' => 'Agent de terrain'],
            ['name' => 'chef_service', 'description' => 'Chef de service'],
            ['name' => 'superviseur', 'description' => 'Superviseur régional'],
            ['name' => 'comptable', 'description' => 'Responsable comptabilité']
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}