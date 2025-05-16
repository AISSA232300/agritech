<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'pivot_id',
        'type',
        'title',
        'message',
        'details',
        'action_taken',
        'task_id',
        'is_resolved',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'details' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the pivot that owns the alert.
     */
    public function pivot()
    {
        return $this->belongsTo(Pivot::class);
    }

    /**
     * Get the task associated with this alert.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who resolved the alert.
     */
    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
