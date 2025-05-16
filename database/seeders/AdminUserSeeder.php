<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get admin role
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run the RoleSeeder first.');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@agritech-bechar.dz',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Create admin preferences
        UserPreference::create([
            'user_id' => $admin->id,
            'language' => 'fr',
            'theme' => 'light',
        ]);

        $this->command->info('Admin user created successfully.');
    }
}
