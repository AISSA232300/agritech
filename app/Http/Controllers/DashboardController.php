<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        // Get user's weather settings
        $weatherSettings = $user->weatherSettings()->where('is_active', true)->first();

        // Get user's preferences
        $preferences = $user->preference;

        return view('dashboard', compact('user', 'weatherSettings', 'preferences'));
    }
}
