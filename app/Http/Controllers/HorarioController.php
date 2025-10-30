<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Periodo;

class HorarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $docente = Docente::where('id_usuario', $usuario->id_usuario)->first();

        if (!$docente) {
            return back()->with('error', 'No se encontró información del docente asociado.');
        }

        $cursos = $docente->cursos()->select('cursos.id_curso')->pluck('cursos.id_curso');

        $horarios = Horario::with('curso')
            ->whereIn('id_curso', $cursos)
            ->orderByRaw("FIELD(dia, 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado')")
            ->orderBy('hora_inicio')
            ->get();

        // ✅ Obtener periodo activo
        $periodo = Periodo::where('estado', 1)->first();

        return view('docente.horario', compact('docente', 'horarios', 'periodo'));
    }
}
