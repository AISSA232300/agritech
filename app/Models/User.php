<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'profile_photo',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the weather settings for the user.
     */
    public function weatherSettings()
    {
        return $this->hasMany(WeatherSetting::class);
    }

    /**
     * Get the preferences for the user.
     */
    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a gestionnaire.
     */
    public function isGestionnaire()
    {
        return $this->hasRole('gestionnaire');
    }

    /**
     * Check if the user is an employe.
     */
    public function isEmploye()
    {
        return $this->hasRole('employe');
    }

    /**
     * Check if the user has management privileges (admin or gestionnaire).
     */
    public function hasManagementAccess()
    {
        return $this->isAdmin() || $this->isGestionnaire();
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get the pivots for the user.
     */
    public function pivots()
    {
        return $this->hasMany(Pivot::class);
    }

    /**
     * Get the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the tasks completed by the user.
     */
    public function completedTasks()
    {
        return $this->hasMany(Task::class, 'completed_by');
    }

    /**
     * Get the alerts resolved by the user.
     */
    public function resolvedAlerts()
    {
        return $this->hasMany(Alert::class, 'resolved_by');
    }
}
