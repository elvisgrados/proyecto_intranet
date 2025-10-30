<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Autoevaluacion;
use App\Models\AsignacionDocente;

class EvaluacionesDocenteController extends Controller
{
    public function index()
    {
        $idUsuario = Auth::user()->id;
        $idDocente = \DB::table('docentes')
            ->where('id_usuario', $idUsuario)
            ->value('id_docente');

        $idCursos = AsignacionDocente::where('id_docente', $idDocente)
            ->pluck('id_curso');

        $evaluaciones = Autoevaluacion::whereIn('id_curso', $idCursos)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('docente.evaluaciones', compact('evaluaciones'));
    }
}
