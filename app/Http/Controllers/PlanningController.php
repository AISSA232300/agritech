<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Pivot;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the planning calendar.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get all pivots the user has access to
        if ($user->hasManagementAccess()) {
            $pivots = Pivot::all();
        } else {
            $pivots = Pivot::where('user_id', $user->id)->get();
        }
        
        // Get tasks for the calendar
        if ($user->hasManagementAccess()) {
            $tasks = Task::with(['user', 'pivot', 'completedBy'])
                ->whereDate('date', '>=', now()->subDays(30))
                ->whereDate('date', '<=', now()->addDays(60))
                ->get();
        } else {
            $tasks = Task::where('user_id', $user->id)
                ->with(['user', 'pivot', 'completedBy'])
                ->whereDate('date', '>=', now()->subDays(30))
                ->whereDate('date', '<=', now()->addDays(60))
                ->get();
        }
        
        // Format tasks for the calendar
        $events = [];
        foreach ($tasks as $task) {
            $events[] = [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->date->format('Y-m-d') . ($task->start_time ? 'T' . $task->start_time->format('H:i:s') : ''),
                'end' => $task->date->format('Y-m-d') . ($task->end_time ? 'T' . $task->end_time->format('H:i:s') : ''),
                'allDay' => !$task->start_time,
                'className' => $this->getEventClass($task),
                'extendedProps' => [
                    'description' => $task->description,
                    'pivot' => $task->pivot->name,
                    'category' => $task->category,
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'completed_by' => $task->completed_by ? $task->completedBy->name : null,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('d/m/Y H:i') : null,
                ]
            ];
        }
        
        return view('planning.index', compact('pivots', 'events'));
    }
    
    /**
     * Get the CSS class for a task event based on its properties.
     *
     * @param  \App\Models\Task  $task
     * @return string
     */
    private function getEventClass(Task $task)
    {
        // Base class
        $class = 'fc-event-';
        
        // Add status class
        if ($task->status === 'completed') {
            $class .= 'success';
        } elseif ($task->status === 'in_progress') {
            $class .= 'primary';
        } else {
            // Add priority class for pending tasks
            switch ($task->priority) {
                case 'high':
                    $class .= 'danger';
                    break;
                case 'medium':
                    $class .= 'warning';
                    break;
                default:
                    $class .= 'info';
            }
        }
        
        return $class;
    }
}
