<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario logueado es un alumno
        if (Auth::check() && Auth::user()->id_tipo === 3    ) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Acceso no autorizado.');
    }
}
