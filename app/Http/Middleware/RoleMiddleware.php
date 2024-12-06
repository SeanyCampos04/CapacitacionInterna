<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {
            // Obtén el usuario autenticado
            $user = Auth::user();

            // Verifica si el usuario tiene alguno de los roles especificados
            foreach ($roles as $role) {
                if ($user->roles->contains('nombre', $role)) {
                    return $next($request); // Permite el acceso si tiene el rol
                }
            }

            // Si no tiene ninguno de los roles, muestra un error o redirige
            return redirect()->route('inicio')->with('error', 'No tienes acceso a esta página');
        }

        // Si el usuario no está autenticado, redirige al login
        return redirect()->route('login');
    }
}
