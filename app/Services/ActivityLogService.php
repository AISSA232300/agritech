<?php

namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log a user activity
     *
     * @param string $action The action performed (e.g., 'create', 'update', 'delete')
     * @param string $module The module where the action was performed (e.g., 'planification', 'pivot')
     * @param string|null $description A description of the activity
     * @param array|null $details Additional details about the activity
     * @return UserActivity|null
     */
    public static function log($action, $module, $description = null, $details = null)
    {
        if (!Auth::check()) {
            return null;
        }
        
        return UserActivity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'details' => $details,
        ]);
    }
    
    /**
     * Get recent activities for a user
     *
     * @param int $userId The user ID
     * @param int $limit The maximum number of activities to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRecentActivities($userId, $limit = 10)
    {
        return UserActivity::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
