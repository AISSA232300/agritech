<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's role
        $userRole = auth()->user()->role->name;

        // Check if user has the required role
        if ($role === 'gestionnaire' && $userRole !== 'gestionnaire' && $userRole !== 'admin') {
            abort(403, 'Accès non autorisé. Vous devez être gestionnaire pour accéder à cette page.');
        }

        // For employe role, allow both employe and higher roles (gestionnaire, admin)
        if ($role === 'employe' && $userRole !== 'employe' && $userRole !== 'gestionnaire' && $userRole !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
