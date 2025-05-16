<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\UserPreference;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrateur']);
        $techRole = Role::firstOrCreate(['name' => 'technicien'], ['description' => 'Technicien']);
        $agricRole = Role::firstOrCreate(['name' => 'agriculteur'], ['description' => 'Agriculteur']);

        // Create admin user
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@agritech-bechar.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]
        );

        // Create technician user
        $tech = User::firstOrCreate(
            ['username' => 'technicien'],
            [
                'name' => 'Ahmed Benali',
                'email' => 'technicien@agritech-bechar.com',
                'password' => Hash::make('tech123'),
                'role_id' => $techRole->id,
                'is_active' => true,
            ]
        );

        // Create farmer user
        $agric = User::firstOrCreate(
            ['username' => 'agriculteur'],
            [
                'name' => 'Mohamed Saidi',
                'email' => 'agriculteur@agritech-bechar.com',
                'password' => Hash::make('agric123'),
                'role_id' => $agricRole->id,
                'is_active' => true,
            ]
        );

        // Create user preferences
        foreach ([$admin, $tech, $agric] as $user) {
            UserPreference::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'language' => 'fr',
                    'theme' => 'light',
                    'notifications_enabled' => true,
                ]
            );
        }
    }
}
