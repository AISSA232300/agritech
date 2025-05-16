<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Pivot;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get pivots, users, and tasks
        $pivots = Pivot::all();
        $users = User::all();
        $tasks = Task::all();
        
        if ($pivots->isEmpty() || $users->isEmpty()) {
            $this->command->error('No pivots or users found. Please run the PivotSeeder and UserSeeder first.');
            return;
        }

        // Alert types
        $types = ['critical', 'warning', 'info'];
        
        // Create some alerts
        $alertsCount = 15;
        
        for ($i = 0; $i < $alertsCount; $i++) {
            $pivot = $pivots->random();
            $type = $types[array_rand($types)];
            
            // Generate a random date within the last 14 days
            $createdAt = Carbon::now()->subDays(rand(0, 14))->subHours(rand(0, 23));
            
            // Determine if the alert is resolved
            $isResolved = (bool) rand(0, 1);
            $resolvedAt = $isResolved ? $createdAt->copy()->addHours(rand(1, 5)) : null;
            $resolvedBy = $isResolved ? $users->random()->id : null;
            
            // Randomly associate with a task
            $taskId = rand(0, 3) > 0 ? null : ($tasks->isNotEmpty() ? $tasks->random()->id : null);
            
            // Generate alert details based on type
            switch ($type) {
                case 'critical':
                    $title = 'Alerte critique';
                    $message = 'Problème critique détecté sur ' . $pivot->name;
                    $details = json_encode([
                        'sensor' => 'temperature',
                        'value' => rand(40, 50),
                        'threshold' => 40,
                        'unit' => '°C'
                    ]);
                    $actionTaken = $isResolved ? 'Irrigation d\'urgence déclenchée' : null;
                    break;
                    
                case 'warning':
                    $title = 'Avertissement';
                    $message = 'Niveau d\'humidité anormal sur ' . $pivot->name;
                    $details = json_encode([
                        'sensor' => 'humidity',
                        'value' => rand(10, 20),
                        'threshold' => 20,
                        'unit' => '%'
                    ]);
                    $actionTaken = $isResolved ? 'Ajustement de l\'irrigation' : null;
                    break;
                    
                default: // info
                    $title = 'Information';
                    $message = 'Maintenance préventive recommandée pour ' . $pivot->name;
                    $details = json_encode([
                        'maintenance_type' => 'preventive',
                        'components' => ['buses', 'filtres', 'capteurs']
                    ]);
                    $actionTaken = $isResolved ? 'Maintenance effectuée' : null;
                    break;
            }
            
            Alert::create([
                'pivot_id' => $pivot->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'details' => $details,
                'action_taken' => $actionTaken,
                'task_id' => $taskId,
                'is_resolved' => $isResolved,
                'resolved_at' => $resolvedAt,
                'resolved_by' => $resolvedBy,
                'created_at' => $createdAt,
                'updated_at' => $isResolved ? $resolvedAt : $createdAt,
            ]);
        }

        $this->command->info('Alerts created successfully.');
    }
}
