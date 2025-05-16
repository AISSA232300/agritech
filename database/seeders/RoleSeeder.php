<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default roles
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrateur avec accès complet',
            ],
            [
                'name' => 'technicien',
                'description' => 'Technicien responsable de la maintenance',
            ],
            [
                'name' => 'agriculteur',
                'description' => 'Agriculteur qui gère les cultures',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
