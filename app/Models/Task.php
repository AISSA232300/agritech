<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pivot_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'category',
        'priority',
        'status',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pivot that owns the task.
     */
    public function pivot()
    {
        return $this->belongsTo(Pivot::class);
    }

    /**
     * Get the user who completed the task.
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the alerts associated with this task.
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
