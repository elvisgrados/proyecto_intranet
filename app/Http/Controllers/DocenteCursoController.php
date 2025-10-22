<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;
use App\Models\AsignacionDocente;
use App\Models\Curso;
use App\Models\Autoevaluacion;

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

    public function verCurso($id){
        $curso = \App\Models\Curso::with(['temas', 'autoevaluaciones'])
            ->findOrFail($id);

        // Agrupar por semana
        $temasPorSemana = $curso->temas->groupBy('semana');
        $autoevaluacionesPorSemana = $curso->autoevaluaciones->groupBy('semana');

        return view('docente.curso-detalle', compact('curso', 'temasPorSemana', 'autoevaluacionesPorSemana'));
    }

    public function verResultados($id)
    {
        $evaluacion = Autoevaluacion::with('resultados.alumno.usuario')
            ->findOrFail($id);

        return view('docente.resultados-evaluacion', compact('evaluacion'));
    }
}
