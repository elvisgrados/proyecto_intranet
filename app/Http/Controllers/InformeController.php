<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;
use App\Models\AsignacionDocente;
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\AlumnoCurso;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Periodo;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;

class InformeController extends Controller
{
    public function index()
{
    // obtener usuario autenticado
    $user = Auth::user();
    $userId = $user->id_usuario ?? $user->id ?? null;
    $docente = Docente::where('id_usuario', $userId)->first();

    // obtener todos los cursos (no solo los del docente)
    $cursos = Curso::all();

    // agregar (Actual) a periodos
    $periodos = Periodo::orderByDesc('id_periodo')->get()->map(function ($p) {
        if ($p->estado == 1) {
            $p->nombre .= ' (Actual)';
        }
        return $p;
    });

    return view('tutor.informes', compact('cursos', 'periodos'));
}



    public function filtrar(Request $request){
        $request->validate([
            'periodo' => 'required|integer',
            'curso' => 'nullable|integer',
        ]);

        $cursoId = $request->curso ? (int)$request->curso : null;
        $periodoId = (int)$request->periodo;

        $user = Auth::user();
        $userId = $user->id_usuario ?? $user->id ?? null;
        $tutor = Docente::where('id_usuario', $userId)->first();

        $cursos = Curso::all();

        $periodos = Periodo::orderByDesc('id_periodo')->get()->map(function($p){
            if ($p->estado == 1) { 
                $p->nombre .= ' (Actual)'; 
            }
            return $p;
        });

        // 🔹 Si no se selecciona curso → traer todos los alumnos del periodo
        $matriculasQuery = AlumnoCurso::where('id_periodo', $periodoId)
            ->with(['alumno.usuario']);

        if ($cursoId) {
            $matriculasQuery->where('id_curso', $cursoId);
        }

        $matriculas = $matriculasQuery->get();

        $alumnosData = [];
        $sumaPromedios = 0;
        $sumaAsistencias = 0;

        // 🔹 Evaluaciones únicas (por todos los cursos si no se eligió uno)
        $evaluacionesQuery = Nota::query();
        if ($cursoId) {
            $evaluacionesQuery->where('id_curso', $cursoId);
        }
        $evaluacionesUnicas = $evaluacionesQuery
            ->whereNotNull('puntaje')
            ->distinct('evaluacion')
            ->pluck('evaluacion')
            ->filter()
            ->count();

        foreach ($matriculas as $mat) {
            $alumno = $mat->alumno;
            if (!$alumno) continue;

            $notasQuery = Nota::where('id_alumno', $alumno->id_alumno);
            $asistQuery = Asistencia::where('id_alumno', $alumno->id_alumno);

            if ($cursoId) {
                $notasQuery->where('id_curso', $cursoId);
                $asistQuery->where('id_curso', $cursoId);
            }

            $promedio = $notasQuery->avg('puntaje');
            $promedio = $promedio !== null ? round((float)$promedio, 2) : null;

            $totalAsistencias = $asistQuery->count();
            $presentes = $asistQuery->where(function($q){
                $q->where('estado','Presente')
                ->orWhere('estado','P presente')
                ->orWhere('puntaje','>',0);
            })->count();

            $porcAsistencia = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 0) : null;

            $estado = 'Sin datos';
            if ($promedio !== null || $porcAsistencia !== null) {
                if (($promedio >= 16) && ($porcAsistencia >= 90)) {
                    $estado = 'Destacada';
                } elseif (($promedio < 11) || ($porcAsistencia < 75)) {
                    $estado = 'En riesgo';
                } else {
                    $estado = 'Regular';
                }
            }

            $alumnosData[] = [
                'id_alumno' => $alumno->id_alumno,
                'nombres' => $alumno->usuario->nombres ?? ('Alumno '.$alumno->id_alumno),
                'apellidos' => $alumno->usuario->apellidos ?? '',
                'promedio' => $promedio,
                'asistencia' => $porcAsistencia,
                'estado' => $estado,
            ];

            if ($promedio !== null) $sumaPromedios += $promedio;
            if ($porcAsistencia !== null) $sumaAsistencias += ($porcAsistencia ?? 0);
        }

        $countAlumnos = count($alumnosData);
        $promedioGeneral = $countAlumnos > 0 ? round($sumaPromedios / $countAlumnos, 2) : null;
        $asistenciaPromedio = $countAlumnos > 0 ? round($sumaAsistencias / $countAlumnos, 0) : null;
        $alumnosEnRiesgo = collect($alumnosData)->where('estado', 'En riesgo')->count();

        return view('tutor.informes', [
            'cursos' => $cursos,
            'periodos' => $periodos,
            'cursoSeleccionado' => $cursoId,
            'periodoSeleccionado' => $periodoId,
            'promedioGeneral' => $promedioGeneral,
            'asistenciaPromedio' => $asistenciaPromedio,
            'evaluacionesRealizadas' => $evaluacionesUnicas,
            'alumnosEnRiesgo' => $alumnosEnRiesgo,
            'alumnos' => $alumnosData,
        ]);
    }



    // detalle por alumno (puede usarse en modal)
    public function detalleAlumno($id){
        $alumno = Alumno::with(['usuario','notas' => function($q){ $q->orderBy('fecha_registro','desc'); }, 'asistencias' => function($q){ $q->orderBy('fecha','desc'); }])->find($id);

        if (! $alumno) {
            abort(404, 'Alumno no encontrado');
        }

        // resumen de promedios por curso (opcional)
        $notas = $alumno->notas()->get();
        $asistencias = $alumno->asistencias()->get();

        return view('tutor.informes_alumno_detalle', compact('alumno','notas','asistencias'));
    }

    public function descargarPDF($id){
        $alumno = Alumno::with('usuario')->findOrFail($id);
        $notas = DB::table('notas')->where('id_alumno', $id)->get();
        $asistencias = DB::table('asistencias')->where('id_alumno', $id)->get();

        $pdf = Pdf::loadView('tutor.detalle_alumno_pdf', compact('alumno', 'notas', 'asistencias'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('detalle_alumno_'.$alumno->usuario->nombres.'.pdf');
    }
}
