<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si no se especifican roles, permitir acceso
        if (empty($roles)) {
            return $next($request);
        }

        // Verificar si el usuario tiene alguno de los roles requeridos
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // Si el usuario no tiene los roles necesarios, redirigir según su rol actual
        return $this->redirectBasedOnUserRole($user);
    }

    private function redirectBasedOnUserRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isRRHH()) {
            return redirect()->route('rrhh.dashboard');
        } elseif ($user->isJefeArea()) {
            return redirect()->route('jefe.dashboard');
        } elseif ($user->isPortal()) {
            return redirect()->route('portal.dashboard');
        }

        // Si no tiene rol definido, ir al dashboard por defecto
        return redirect()->route('dashboard');
    }
} 