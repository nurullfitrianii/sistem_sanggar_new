<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = strtolower(auth()->user()->role);
        $roles = array_map('strtolower', $roles);

        if (!in_array($userRole, $roles, true)) {
            $dashboardRoute = 'home';
            if ($userRole === 'ketua') $dashboardRoute = 'dashboard.ketua';
            elseif ($userRole === 'bendahara') $dashboardRoute = 'dashboard.bendahara';
            elseif ($userRole === 'humas') $dashboardRoute = 'dashboard.humas';
            elseif ($userRole === 'siswa') $dashboardRoute = 'dashboard.siswa';

            return redirect()->route($dashboardRoute)->with('error_403', 'Akses Ditolak! Halaman tersebut hanya untuk peran admin tertentu.');
        }

        return $next($request);
    }
}

