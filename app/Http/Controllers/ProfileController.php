<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ProfileController extends Controller
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
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $recentActivities = ActivityLogService::getRecentActivities($user->id, 5);
        return view('profile.index', compact('user', 'recentActivities'));
    }

    /**
     * Update the user profile.
     *
     * This method is now disabled as profile information should be read-only.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Profile information is now read-only
        return redirect()->route('profile.index')->with('info', 'Les informations du profil ne peuvent pas être modifiées.');
    }

    /**
     * Show the change password form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = bcrypt($validated['password']);
        $user->save();

        // Log the activity
        ActivityLogService::log(
            'update',
            'profile',
            'Changement de mot de passe'
        );

        return redirect()->route('profile.index')->with('success', 'Mot de passe changé avec succès.');
    }
}
