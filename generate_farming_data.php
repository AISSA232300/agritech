<?php

/**
 * Generate Realistic Farming Data
 * 
 * This file provides functions to generate realistic random farming data
 * for the dashboard when actual sensor data is not available.
 */

/**
 * Generate a random value within a range with normal distribution
 * 
 * @param float $min Minimum value
 * @param float $max Maximum value
 * @param float $mean Mean value (center of distribution)
 * @param float $stdDev Standard deviation
 * @return float Random value
 */
function randomNormal($min, $max, $mean, $stdDev) {
    // Box-Muller transform to generate normal distribution
    $u1 = mt_rand() / mt_getrandmax();
    $u2 = mt_rand() / mt_getrandmax();
    $z0 = sqrt(-2.0 * log($u1)) * cos(2.0 * M_PI * $u2);
    
    // Scale and shift to desired range
    $value = $z0 * $stdDev + $mean;
    
    // Clamp to min/max
    return max($min, min($max, $value));
}

/**
 * Get a random wind direction
 * 
 * @return string Wind direction (N, NE, E, SE, S, SW, W, NW)
 */
function getRandomWindDirection() {
    $directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
    return $directions[array_rand($directions)];
}

/**
 * Generate realistic farming data for a specific season and time of day
 * 
 * @param string $season Season (spring, summer, fall, winter)
 * @param string $timeOfDay Time of day (morning, afternoon, evening, night)
 * @return array Farming data
 */
function generateFarmingData($season = null, $timeOfDay = null) {
    // If season not specified, determine based on current month
    if ($season === null) {
        $month = (int)date('n');
        if ($month >= 3 && $month <= 5) {
            $season = 'spring';
        } elseif ($month >= 6 && $month <= 8) {
            $season = 'summer';
        } elseif ($month >= 9 && $month <= 11) {
            $season = 'fall';
        } else {
            $season = 'winter';
        }
    }
    
    // If time of day not specified, determine based on current hour
    if ($timeOfDay === null) {
        $hour = (int)date('G');
        if ($hour >= 6 && $hour < 12) {
            $timeOfDay = 'morning';
        } elseif ($hour >= 12 && $hour < 17) {
            $timeOfDay = 'afternoon';
        } elseif ($hour >= 17 && $hour < 22) {
            $timeOfDay = 'evening';
        } else {
            $timeOfDay = 'night';
        }
    }
    
    // Base ranges for Béchar, Algeria climate (semi-arid)
    $ranges = [
        'spring' => [
            'soil_moisture' => ['min' => 20, 'max' => 45, 'mean' => 30, 'stdDev' => 5],
            'soil_temperature' => ['min' => 15, 'max' => 25, 'mean' => 20, 'stdDev' => 2],
            'air_temperature' => [
                'morning' => ['min' => 15, 'max' => 25, 'mean' => 20, 'stdDev' => 2],
                'afternoon' => ['min' => 25, 'max' => 35, 'mean' => 30, 'stdDev' => 2],
                'evening' => ['min' => 20, 'max' => 30, 'mean' => 25, 'stdDev' => 2],
                'night' => ['min' => 10, 'max' => 20, 'mean' => 15, 'stdDev' => 2]
            ],
            'air_humidity' => ['min' => 30, 'max' => 60, 'mean' => 45, 'stdDev' => 5],
            'light_intensity' => [
                'morning' => ['min' => 400, 'max' => 700, 'mean' => 550, 'stdDev' => 50],
                'afternoon' => ['min' => 700, 'max' => 1000, 'mean' => 850, 'stdDev' => 50],
                'evening' => ['min' => 200, 'max' => 500, 'mean' => 350, 'stdDev' => 50],
                'night' => ['min' => 0, 'max' => 10, 'mean' => 5, 'stdDev' => 2]
            ],
            'rainfall' => ['min' => 0, 'max' => 5, 'mean' => 0.5, 'stdDev' => 1],
            'wind_speed' => ['min' => 5, 'max' => 20, 'mean' => 12, 'stdDev' => 3]
        ],
        'summer' => [
            'soil_moisture' => ['min' => 10, 'max' => 30, 'mean' => 20, 'stdDev' => 5],
            'soil_temperature' => ['min' => 25, 'max' => 40, 'mean' => 32, 'stdDev' => 3],
            'air_temperature' => [
                'morning' => ['min' => 25, 'max' => 35, 'mean' => 30, 'stdDev' => 2],
                'afternoon' => ['min' => 35, 'max' => 45, 'mean' => 40, 'stdDev' => 2],
                'evening' => ['min' => 30, 'max' => 40, 'mean' => 35, 'stdDev' => 2],
                'night' => ['min' => 20, 'max' => 30, 'mean' => 25, 'stdDev' => 2]
            ],
            'air_humidity' => ['min' => 15, 'max' => 40, 'mean' => 25, 'stdDev' => 5],
            'light_intensity' => [
                'morning' => ['min' => 600, 'max' => 900, 'mean' => 750, 'stdDev' => 50],
                'afternoon' => ['min' => 900, 'max' => 1200, 'mean' => 1050, 'stdDev' => 50],
                'evening' => ['min' => 300, 'max' => 600, 'mean' => 450, 'stdDev' => 50],
                'night' => ['min' => 0, 'max' => 10, 'mean' => 5, 'stdDev' => 2]
            ],
            'rainfall' => ['min' => 0, 'max' => 2, 'mean' => 0.1, 'stdDev' => 0.5],
            'wind_speed' => ['min' => 5, 'max' => 25, 'mean' => 15, 'stdDev' => 4]
        ],
        'fall' => [
            'soil_moisture' => ['min' => 15, 'max' => 40, 'mean' => 25, 'stdDev' => 5],
            'soil_temperature' => ['min' => 15, 'max' => 30, 'mean' => 22, 'stdDev' => 3],
            'air_temperature' => [
                'morning' => ['min' => 15, 'max' => 25, 'mean' => 20, 'stdDev' => 2],
                'afternoon' => ['min' => 25, 'max' => 35, 'mean' => 30, 'stdDev' => 2],
                'evening' => ['min' => 20, 'max' => 30, 'mean' => 25, 'stdDev' => 2],
                'night' => ['min' => 10, 'max' => 20, 'mean' => 15, 'stdDev' => 2]
            ],
            'air_humidity' => ['min' => 25, 'max' => 55, 'mean' => 40, 'stdDev' => 5],
            'light_intensity' => [
                'morning' => ['min' => 400, 'max' => 700, 'mean' => 550, 'stdDev' => 50],
                'afternoon' => ['min' => 700, 'max' => 1000, 'mean' => 850, 'stdDev' => 50],
                'evening' => ['min' => 200, 'max' => 500, 'mean' => 350, 'stdDev' => 50],
                'night' => ['min' => 0, 'max' => 10, 'mean' => 5, 'stdDev' => 2]
            ],
            'rainfall' => ['min' => 0, 'max' => 10, 'mean' => 1, 'stdDev' => 2],
            'wind_speed' => ['min' => 5, 'max' => 20, 'mean' => 12, 'stdDev' => 3]
        ],
        'winter' => [
            'soil_moisture' => ['min' => 25, 'max' => 50, 'mean' => 35, 'stdDev' => 5],
            'soil_temperature' => ['min' => 5, 'max' => 15, 'mean' => 10, 'stdDev' => 2],
            'air_temperature' => [
                'morning' => ['min' => 5, 'max' => 15, 'mean' => 10, 'stdDev' => 2],
                'afternoon' => ['min' => 15, 'max' => 25, 'mean' => 20, 'stdDev' => 2],
                'evening' => ['min' => 10, 'max' => 20, 'mean' => 15, 'stdDev' => 2],
                'night' => ['min' => 0, 'max' => 10, 'mean' => 5, 'stdDev' => 2]
            ],
            'air_humidity' => ['min' => 40, 'max' => 70, 'mean' => 55, 'stdDev' => 5],
            'light_intensity' => [
                'morning' => ['min' => 300, 'max' => 600, 'mean' => 450, 'stdDev' => 50],
                'afternoon' => ['min' => 600, 'max' => 900, 'mean' => 750, 'stdDev' => 50],
                'evening' => ['min' => 100, 'max' => 400, 'mean' => 250, 'stdDev' => 50],
                'night' => ['min' => 0, 'max' => 10, 'mean' => 5, 'stdDev' => 2]
            ],
            'rainfall' => ['min' => 0, 'max' => 15, 'mean' => 3, 'stdDev' => 3],
            'wind_speed' => ['min' => 10, 'max' => 30, 'mean' => 20, 'stdDev' => 4]
        ]
    ];
    
    // Get the appropriate ranges for the current season and time of day
    $seasonRanges = $ranges[$season];
    
    // Generate farming data
    $data = [
        'soil_moisture' => round(randomNormal(
            $seasonRanges['soil_moisture']['min'],
            $seasonRanges['soil_moisture']['max'],
            $seasonRanges['soil_moisture']['mean'],
            $seasonRanges['soil_moisture']['stdDev']
        ), 1),
        'soil_temperature' => round(randomNormal(
            $seasonRanges['soil_temperature']['min'],
            $seasonRanges['soil_temperature']['max'],
            $seasonRanges['soil_temperature']['mean'],
            $seasonRanges['soil_temperature']['stdDev']
        ), 1),
        'air_temperature' => round(randomNormal(
            $seasonRanges['air_temperature'][$timeOfDay]['min'],
            $seasonRanges['air_temperature'][$timeOfDay]['max'],
            $seasonRanges['air_temperature'][$timeOfDay]['mean'],
            $seasonRanges['air_temperature'][$timeOfDay]['stdDev']
        ), 1),
        'air_humidity' => round(randomNormal(
            $seasonRanges['air_humidity']['min'],
            $seasonRanges['air_humidity']['max'],
            $seasonRanges['air_humidity']['mean'],
            $seasonRanges['air_humidity']['stdDev']
        ), 1),
        'light_intensity' => round(randomNormal(
            $seasonRanges['light_intensity'][$timeOfDay]['min'],
            $seasonRanges['light_intensity'][$timeOfDay]['max'],
            $seasonRanges['light_intensity'][$timeOfDay]['mean'],
            $seasonRanges['light_intensity'][$timeOfDay]['stdDev']
        ), 1),
        'rainfall' => round(randomNormal(
            $seasonRanges['rainfall']['min'],
            $seasonRanges['rainfall']['max'],
            $seasonRanges['rainfall']['mean'],
            $seasonRanges['rainfall']['stdDev']
        ), 1),
        'wind_speed' => round(randomNormal(
            $seasonRanges['wind_speed']['min'],
            $seasonRanges['wind_speed']['max'],
            $seasonRanges['wind_speed']['mean'],
            $seasonRanges['wind_speed']['stdDev']
        ), 1),
        'wind_direction' => getRandomWindDirection(),
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    return $data;
}

/**
 * Generate data for dashboard display
 * 
 * @param int $pivotId Optional pivot ID to associate with the data
 * @return array Dashboard data
 */
function generateDashboardData($pivotId = null) {
    $farmingData = generateFarmingData();
    
    // Add additional dashboard-specific data
    $dashboardData = array_merge($farmingData, [
        'pivot_id' => $pivotId,
        'irrigation_status' => (mt_rand(0, 100) < 30) ? 'active' : 'inactive',
        'battery_level' => mt_rand(60, 100),
        'signal_strength' => mt_rand(70, 100),
        'last_irrigation' => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 48) . ' hours')),
        'next_irrigation' => date('Y-m-d H:i:s', strtotime('+' . mt_rand(1, 24) . ' hours')),
        'water_usage' => round(mt_rand(100, 500) / 10, 1),
        'crop_health' => ['excellent', 'good', 'fair', 'poor'][mt_rand(0, 3)],
        'growth_stage' => ['germination', 'vegetative', 'flowering', 'ripening', 'harvest'][mt_rand(0, 4)]
    ]);
    
    return $dashboardData;
}

// Example usage:
// $data = generateFarmingData();
// echo "Soil Moisture: {$data['soil_moisture']}%\n";
// echo "Air Temperature: {$data['air_temperature']}°C\n";
// 
// $dashboardData = generateDashboardData(1);
// echo "Irrigation Status: {$dashboardData['irrigation_status']}\n";
// echo "Crop Health: {$dashboardData['crop_health']}\n";
