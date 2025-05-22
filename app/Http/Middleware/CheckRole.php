<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Si le rôle requis est 'admin', les super_admin peuvent aussi accéder
        if ($role === 'admin' && ($user->role === 'admin' || $user->role === 'super_admin')) {
            return $next($request);
        }
        
        // Si le rôle requis est 'super_admin', seuls les super_admin peuvent accéder
        if ($role === 'super_admin' && $user->role === 'super_admin') {
            return $next($request);
        }
        
        // Si l'utilisateur n'a pas le rôle requis
        return redirect('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}
