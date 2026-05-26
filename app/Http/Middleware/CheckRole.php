<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect('/login')->withErrors(['login' => 'Silahkan login terlebih dahulu.']);
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        
        $rolesAllowed = array_map('strtolower', $roles);
        
        if (in_array(strtolower($user->role), $rolesAllowed)) {
            return $next($request);
        }

        abort(403, 'Akses tidak diizinkan untuk role Anda.');
    }
}
