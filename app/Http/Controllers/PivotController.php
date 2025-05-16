<?php

namespace App\Http\Controllers;

use App\Models\Pivot;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class PivotController extends Controller
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
        
        // If user is gestionnaire or admin, show all pivots
        if ($user->hasManagementAccess()) {
            $pivots = Pivot::with('user')->latest()->paginate(10);
        } else {
            // For employe, only show pivots assigned to them
            $pivots = Pivot::where('user_id', $user->id)
                ->with('user')
                ->latest()
                ->paginate(10);
        }
        
        return view('pivots.index', compact('pivots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only gestionnaire can create pivots
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('pivots.index')
                ->with('error', 'Vous n\'avez pas la permission de créer des pivots.');
        }
        
        return view('pivots.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only gestionnaire can create pivots
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('pivots.index')
                ->with('error', 'Vous n\'avez pas la permission de créer des pivots.');
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'surface_area' => 'required|numeric|min:0',
            'crop_type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:active,inactive,maintenance',
        ]);
        
        $pivot = Pivot::create($validated);
        
        // Log the activity
        ActivityLogService::log(
            'create',
            'pivots',
            'Création d\'un pivot',
            ['name' => $pivot->name, 'crop_type' => $pivot->crop_type]
        );
        
        return redirect()->route('pivots.index')
            ->with('success', 'Pivot créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pivot  $pivot
     * @return \Illuminate\Http\Response
     */
    public function show(Pivot $pivot)
    {
        // Load related tasks
        $tasks = $pivot->tasks()->with(['user', 'completedBy'])->latest()->get();
        
        // Load sensor data
        $sensorData = $pivot->sensorData()->latest()->first();
        
        return view('pivots.show', compact('pivot', 'tasks', 'sensorData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pivot  $pivot
     * @return \Illuminate\Http\Response
     */
    public function edit(Pivot $pivot)
    {
        // Only gestionnaire can edit pivots
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('pivots.index')
                ->with('error', 'Vous n\'avez pas la permission de modifier des pivots.');
        }
        
        return view('pivots.edit', compact('pivot'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pivot  $pivot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pivot $pivot)
    {
        // Only gestionnaire can update pivots
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('pivots.index')
                ->with('error', 'Vous n\'avez pas la permission de modifier des pivots.');
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'surface_area' => 'required|numeric|min:0',
            'crop_type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:active,inactive,maintenance',
        ]);
        
        $pivot->update($validated);
        
        // Log the activity
        ActivityLogService::log(
            'update',
            'pivots',
            'Mise à jour d\'un pivot',
            ['name' => $pivot->name, 'crop_type' => $pivot->crop_type]
        );
        
        return redirect()->route('pivots.index')
            ->with('success', 'Pivot mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pivot  $pivot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pivot $pivot)
    {
        // Only gestionnaire can delete pivots
        if (!auth()->user()->hasManagementAccess()) {
            return redirect()->route('pivots.index')
                ->with('error', 'Vous n\'avez pas la permission de supprimer des pivots.');
        }
        
        $pivotName = $pivot->name;
        
        $pivot->delete();
        
        // Log the activity
        ActivityLogService::log(
            'delete',
            'pivots',
            'Suppression d\'un pivot',
            ['name' => $pivotName]
        );
        
        return redirect()->route('pivots.index')
            ->with('success', 'Pivot supprimé avec succès.');
    }
}
