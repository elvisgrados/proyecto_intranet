<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;
use App\Models\Asistencia;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        // Docente autenticado
        $usuario = Auth::user();
        $docente = Docente::where('id_usuario', $usuario->id_usuario)->first();

        // Cursos asignados al docente
        $cursos = $docente->cursos()->select('cursos.id_curso', 'cursos.nombre_curso')->get();

        if ($cursos->isEmpty()) {
            return back()->with('error', 'No tienes cursos asignados.');
        }

        $cursoId = $request->curso ?? $cursos->first()->id_curso;
        $fecha = $request->fecha ?? date('Y-m-d');

        $alumnos = \DB::table('alumno_curso')
        ->join('alumnos', 'alumno_curso.id_alumno', '=', 'alumnos.id_alumno')
        ->join('usuarios', 'alumnos.id_usuario', '=', 'usuarios.id_usuario')
        ->leftJoin('asistencias', function ($join) use ($cursoId, $fecha) {
            $join->on('asistencias.id_alumno', '=', 'alumnos.id_alumno')
                ->where('asistencias.id_curso', $cursoId)
                ->where('asistencias.fecha', $fecha);
        })
        ->where('alumno_curso.id_curso', $cursoId)
        ->where('usuarios.id_tipo', 3) // ✅ Solo alumnos
        ->where('usuarios.id_usuario', '!=', Auth::user()->id_usuario) // ✅ Excluir docente
        ->select(
            'alumnos.id_alumno',
            'usuarios.nombres',
            'usuarios.apellidos',
            'asistencias.estado',
            'asistencias.observacion'
        )
        ->distinct() // ✅ Evita duplicados
        ->orderBy('usuarios.apellidos')
        ->get();



        return view('docente.asistencia', compact('cursos', 'cursoId', 'fecha', 'alumnos'));
    }


    public function store(Request $request)
    {
        foreach ($request->alumnos as $idAlumno => $data) {

            $estado = $data['estado'];

            // ✅ Conversión a puntaje
            $puntaje = match ($estado) {
                'Presente' => 100,
                'Tarde' => 50,
                'Ausente' => 0,
                'Justificado' => null,
                default => null
            };

            Asistencia::updateOrCreate(
                [
                    'id_alumno' => $idAlumno,
                    'id_curso' => $request->curso,
                    'fecha' => $request->fecha,
                ],
                [
                    'estado' => $estado,
                    'observacion' => $data['observacion'] ?? null,
                    'puntaje' => $puntaje
                ]
            );
        }

        return back()->with('success', '✅ Asistencia guardada correctamente.');
    }
}
