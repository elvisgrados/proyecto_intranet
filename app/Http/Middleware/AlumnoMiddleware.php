<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar que el usuario esté logueado
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Verificar que el usuario tenga tipo "3" (alumno)
        if (Auth::user()->id_tipo === 3) {
            return $next($request);
        }

        // Si no es alumno, redirigir según tipo
        switch (Auth::user()->id_tipo) {
            case 1:
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Acceso solo para alumnos');
            case 2:
                return redirect()->route('docente.dashboard')
                    ->with('error', 'Acceso solo para alumnos');
            default:
                return redirect('/login');
        }
    }
}
