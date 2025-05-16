<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'pivot_id',
        'soil_moisture',
        'soil_temperature',
        'air_temperature',
        'air_humidity',
        'light_intensity',
        'rainfall',
        'wind_speed',
        'wind_direction',
        'reading_time',
    ];

    protected $casts = [
        'soil_moisture' => 'float',
        'soil_temperature' => 'float',
        'air_temperature' => 'float',
        'air_humidity' => 'float',
        'light_intensity' => 'float',
        'rainfall' => 'float',
        'wind_speed' => 'float',
        'reading_time' => 'datetime',
    ];

    /**
     * Get the pivot that owns the sensor data.
     */
    public function pivot()
    {
        return $this->belongsTo(Pivot::class);
    }
}
