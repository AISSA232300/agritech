<?php

namespace Database\Seeders;

use App\Models\Pivot;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get users and pivots
        $users = User::all();
        $pivots = Pivot::all();
        
        if ($users->isEmpty() || $pivots->isEmpty()) {
            $this->command->error('No users or pivots found. Please run the UserSeeder and PivotSeeder first.');
            return;
        }

        // Task categories
        $categories = ['irrigation', 'fertilization', 'harvest', 'treatment', 'other'];
        
        // Priorities
        $priorities = ['low', 'medium', 'high'];
        
        // Statuses
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        
        // Create some tasks
        $tasksCount = 20;
        
        for ($i = 0; $i < $tasksCount; $i++) {
            $user = $users->random();
            $pivot = $pivots->random();
            $category = $categories[array_rand($categories)];
            $priority = $priorities[array_rand($priorities)];
            $status = $statuses[array_rand($statuses)];
            
            // Generate a random date within the last 30 days
            $date = Carbon::now()->subDays(rand(0, 30))->format('Y-m-d');
            
            // For irrigation tasks, add start and end times
            $startTime = null;
            $endTime = null;
            if ($category === 'irrigation') {
                $hour = rand(6, 18);
                $startTime = sprintf('%02d:00:00', $hour);
                $endTime = sprintf('%02d:00:00', $hour + rand(1, 3));
            }
            
            // For completed tasks, add completed_by and completed_at
            $completedBy = null;
            $completedAt = null;
            if ($status === 'completed') {
                $completedBy = $users->random()->id;
                $completedAt = Carbon::parse($date)->addHours(rand(1, 8));
            }
            
            Task::create([
                'user_id' => $user->id,
                'pivot_id' => $pivot->id,
                'title' => "Tâche de " . ucfirst($category) . " - " . $pivot->name,
                'description' => "Description de la tâche de " . $category . " pour " . $pivot->name,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'category' => $category,
                'priority' => $priority,
                'status' => $status,
                'completed_by' => $completedBy,
                'completed_at' => $completedAt,
            ]);
        }

        $this->command->info('Tasks created successfully.');
    }
}
