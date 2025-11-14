<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario logueado es un tutor
        if (Auth::check() && Auth::user()->id_tipo === 4    ) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Acceso no autorizado.');
    }
}
