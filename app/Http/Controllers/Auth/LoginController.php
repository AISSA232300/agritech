<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (auth()->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is active
            if (!auth()->user()->is_active) {
                auth()->logout();
                return back()->withErrors([
                    'username' => 'Votre compte a été désactivé.',
                ]);
            }

            // Log the login activity with role information
            $userRole = auth()->user()->role->name;
            ActivityLogService::log(
                'login',
                'auth',
                'Connexion au système',
                ['role' => $userRole]
            );

            // Store role in session for easy access
            session(['user_role' => $userRole]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Log the logout activity before logging out
        if (auth()->check()) {
            ActivityLogService::log(
                'logout',
                'auth',
                'Déconnexion du système'
            );
        }

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
