<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'api_provider',
        'api_key',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the weather setting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
