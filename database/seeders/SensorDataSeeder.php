<?php

namespace Database\Seeders;

use App\Models\Pivot;
use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SensorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get pivots
        $pivots = Pivot::all();
        
        if ($pivots->isEmpty()) {
            $this->command->error('No pivots found. Please run the PivotSeeder first.');
            return;
        }

        // Generate sensor data for the last 7 days, every 3 hours
        $days = 7;
        $readingsPerDay = 8; // Every 3 hours
        
        foreach ($pivots as $pivot) {
            for ($day = 0; $day < $days; $day++) {
                for ($reading = 0; $reading < $readingsPerDay; $reading++) {
                    $date = Carbon::now()->subDays($day)->startOfDay()->addHours($reading * 3);
                    
                    // Generate random but realistic sensor values
                    SensorData::create([
                        'pivot_id' => $pivot->id,
                        'soil_moisture' => rand(20, 80), // percentage
                        'soil_temperature' => rand(15, 35), // degrees Celsius
                        'air_temperature' => rand(10, 40), // degrees Celsius
                        'air_humidity' => rand(30, 90), // percentage
                        'light_intensity' => rand(5000, 80000), // lux
                        'rainfall' => rand(0, 50) / 10, // mm
                        'wind_speed' => rand(0, 100) / 10, // km/h
                        'wind_direction' => ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'][rand(0, 7)],
                        'reading_time' => $date,
                    ]);
                }
            }
        }

        $this->command->info('Sensor data created successfully.');
    }
}
