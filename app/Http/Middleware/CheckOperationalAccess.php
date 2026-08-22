<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOperationalAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario autenticado NO tiene empresa (es el admin global/master)
        if (auth()->check() && is_null(auth()->user()->company_id)) {
            // Lo redirigimos a su panel exclusivo de usuarios y le negamos la tienda
            return redirect()->route('usuarios.index')
                ->with('error', 'Los administradores globales no tienen acceso al sistema operativo de la tienda.');
        }

        return $next($request);
    }
}