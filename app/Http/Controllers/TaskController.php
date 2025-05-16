<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class TaskController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        // If user is gestionnaire or admin, show all tasks
        if ($user->hasManagementAccess()) {
            $tasks = Task::with(['user', 'pivot', 'completedBy'])->latest()->paginate(10);
        } else {
            // For employe, only show tasks assigned to them
            $tasks = Task::where('user_id', $user->id)
                ->with(['user', 'pivot', 'completedBy'])
                ->latest()
                ->paginate(10);
        }
        
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only gestionnaire can create tasks
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Vous n\'avez pas la permission de créer des tâches.');
        }
        
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only gestionnaire can create tasks
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Vous n\'avez pas la permission de créer des tâches.');
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pivot_id' => 'required|exists:pivots,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'category' => 'required|string|in:irrigation,fertilisation,traitement,recolte,autre',
            'priority' => 'required|string|in:low,medium,high',
            'status' => 'required|string|in:pending,in_progress,completed',
        ]);
        
        $task = Task::create($validated);
        
        // Log the activity
        ActivityLogService::log(
            'create',
            'tasks',
            'Création d\'une tâche',
            ['title' => $task->title, 'pivot' => $task->pivot->name]
        );
        
        return redirect()->route('tasks.index')
            ->with('success', 'Tâche créée avec succès.');
    }

    /**
     * Mark a task as complete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, Task $task)
    {
        // Check if task is already completed
        if ($task->status === 'completed') {
            return redirect()->back()
                ->with('info', 'Cette tâche est déjà marquée comme terminée.');
        }
        
        // Update task status
        $task->status = 'completed';
        $task->completed_by = auth()->id();
        $task->completed_at = now();
        $task->save();
        
        // Log the activity
        ActivityLogService::log(
            'complete',
            'tasks',
            'Tâche marquée comme terminée',
            [
                'title' => $task->title, 
                'pivot' => $task->pivot->name,
                'completed_by' => auth()->user()->username
            ]
        );
        
        return redirect()->back()
            ->with('success', 'Tâche marquée comme terminée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        // Only gestionnaire can edit tasks
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Vous n\'avez pas la permission de modifier des tâches.');
        }
        
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        // Only gestionnaire can update tasks
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Vous n\'avez pas la permission de modifier des tâches.');
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pivot_id' => 'required|exists:pivots,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'category' => 'required|string|in:irrigation,fertilisation,traitement,recolte,autre',
            'priority' => 'required|string|in:low,medium,high',
            'status' => 'required|string|in:pending,in_progress,completed',
        ]);
        
        $task->update($validated);
        
        // Log the activity
        ActivityLogService::log(
            'update',
            'tasks',
            'Mise à jour d\'une tâche',
            ['title' => $task->title, 'pivot' => $task->pivot->name]
        );
        
        return redirect()->route('tasks.index')
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        // Only gestionnaire can delete tasks
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Vous n\'avez pas la permission de supprimer des tâches.');
        }
        
        $taskTitle = $task->title;
        $pivotName = $task->pivot->name;
        
        $task->delete();
        
        // Log the activity
        ActivityLogService::log(
            'delete',
            'tasks',
            'Suppression d\'une tâche',
            ['title' => $taskTitle, 'pivot' => $pivotName]
        );
        
        return redirect()->route('tasks.index')
            ->with('success', 'Tâche supprimée avec succès.');
    }
}
