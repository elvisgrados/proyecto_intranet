<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;
use App\Models\AsignacionDocente;

class DocenteCursoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Buscar el docente asociado al usuario
        $docente = Docente::where('id_usuario', $usuario->id_usuario)->first();

        if (!$docente) {
            return redirect()->back()->with('error', 'No se encontró un perfil docente asociado a tu cuenta.');
        }

        // Obtener cursos asignados al docente con sus horarios
        $asignaciones = AsignacionDocente::with(['curso.horarios'])
            ->where('id_docente', $docente->id_docente)
            ->get();

        return view('docente.cursos', compact('asignaciones', 'docente'));
    }
}
