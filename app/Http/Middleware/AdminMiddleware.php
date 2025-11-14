<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica que el usuario esté autenticado y sea docente
        if (Auth::check() && Auth::user()->id_tipo === 1) {
            return $next($request);
        }

        // Si no cumple, redirige al login
        return redirect('/login')->with('error', 'Acceso no autorizado.');
    }
}
