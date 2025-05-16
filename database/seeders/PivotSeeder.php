<?php

namespace Database\Seeders;

use App\Models\Pivot;
use App\Models\User;
use Illuminate\Database\Seeder;

class PivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run the UserSeeder first.');
            return;
        }

        // Create default pivots
        $pivots = [
            [
                'name' => 'Pivot 1',
                'surface_area' => 5.5,
                'crop_type' => 'Blé',
                'location' => 'Zone Nord',
                'notes' => 'Pivot principal',
                'status' => 'active',
            ],
            [
                'name' => 'Pivot 2',
                'surface_area' => 4.2,
                'crop_type' => 'Maïs',
                'location' => 'Zone Est',
                'notes' => 'Irrigation goutte à goutte',
                'status' => 'active',
            ],
            [
                'name' => 'Pivot 3',
                'surface_area' => 3.8,
                'crop_type' => 'Tomates',
                'location' => 'Zone Sud',
                'notes' => 'Culture sous serre',
                'status' => 'active',
            ],
            [
                'name' => 'Pivot 4',
                'surface_area' => 6.0,
                'crop_type' => 'Pommes de terre',
                'location' => 'Zone Ouest',
                'notes' => 'Irrigation par aspersion',
                'status' => 'active',
            ],
        ];

        // Assign pivots to users
        foreach ($pivots as $pivotData) {
            // Assign to a random user
            $user = $users->random();
            
            Pivot::create([
                'user_id' => $user->id,
                'name' => $pivotData['name'],
                'surface_area' => $pivotData['surface_area'],
                'crop_type' => $pivotData['crop_type'],
                'location' => $pivotData['location'],
                'notes' => $pivotData['notes'],
                'status' => $pivotData['status'],
            ]);
        }

        $this->command->info('Pivots created successfully.');
    }
}
