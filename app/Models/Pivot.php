<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'surface_area',
        'crop_type',
        'location',
        'notes',
        'status',
    ];

    /**
     * Get the user that owns the pivot.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks for the pivot.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the alerts for the pivot.
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Get the sensor data for the pivot.
     */
    public function sensorData()
    {
        return $this->hasMany(SensorData::class);
    }
}
