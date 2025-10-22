<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Curso;
use App\Models\Alumno;

class AlumnoController extends Controller
{
    
    public function index()
    {
        return view('alumno.dashboard');
    }

    

    public function cursos(Request $request)
    {
        // 🔹 Iniciar consulta con JOIN a docentes
        $query = DB::table('cursos')
            ->leftJoin('docentes', 'cursos.id_docente', '=', 'docentes.id_docente')
            ->select(
                'cursos.id_curso',
                'cursos.nombre_curso',
                'cursos.id_periodo',
                'docentes.nombre_docente',
                'docentes.especialidad'
            );

        // 🔍 Filtro por nombre del curso
        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where('cursos.nombre_curso', 'LIKE', "%{$busqueda}%");
        }

        // 🔹 Filtro por periodo (si se selecciona)
        $periodoSeleccionado = $request->input('id_periodo');
        if ($periodoSeleccionado) {
            $query->where('cursos.id_periodo', $periodoSeleccionado);
        }

        // 🔹 Obtener cursos finales
        $cursos = $query->get();

        // 🔹 Obtener lista de periodos para el dropdown
        $periodos = DB::table('periodos')
            ->select('id_periodo', 'nombre')
            ->orderBy('nombre', 'asc')
            ->get();

        // 🔹 Enviar datos a la vista
        return view('alumno.cursos', compact('cursos', 'periodos', 'periodoSeleccionado'));
    }

    public function horario()
   {
        $idAlumno = Auth::user()->id_alumno;

        $cursos = DB::table('asignacion_alumno AS aa')
            ->join('cursos AS c', 'aa.id_curso', '=', 'c.id_curso')
            ->leftJoin('docentes AS d', 'c.id_docente', '=', 'd.id_docente')
            ->leftJoin('horarios AS h', 'c.id_curso', '=', 'h.id_curso')
            ->select(
                'c.nombre_curso AS nombre_curso', // 🔹 cambia este nombre si es diferente
                'd.nombre_docente',
                'h.dia',
                'h.hora_inicio',
                'h.hora_fin',
                'h.aula'
            )
            ->where('aa.id_alumno', $idAlumno)
            ->orderBy('h.dia')
            ->orderBy('h.hora_inicio')
            ->get();

        return view('alumno.horario', compact('cursos'));
    }

    public function pagos()
    {
        // 🔹 Obtener el alumno autenticado
        $alumno = \App\Models\Alumno::where('id_usuario', Auth::id())->first();

        // Si no hay alumno asociado, evitar error
        if (!$alumno) {
            return back()->with('error', 'No se encontró el registro del alumno.');
        }

        // 🔹 Obtener todos los pagos de ese alumno
        $pagos = \DB::table('pagos')
            ->where('id_alumno', $alumno->id_alumno)
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        // 🔹 Calcular totales
        $total = $pagos->sum('monto');
        $totalPagado = $pagos->where('estado', 'Pagado')->sum('monto');
        $totalPendiente = $total - $totalPagado;

        // 🔹 Enviar datos a la vista
        return view('alumno.pagos', compact('pagos', 'total', 'totalPagado', 'totalPendiente'));
    }


    public function resultados()
    {
        $resultados = DB::table('simulacros')
            ->select('titulo', 'fecha', 'descripcion', 'puntaje', 'id_simulacro')
            ->orderBy('fecha', 'asc')
            ->get();

        $labels = $resultados->pluck('titulo');
        $puntajes = $resultados->pluck('puntaje');

        return view('alumno.resultados', compact('resultados', 'labels', 'puntajes'));
    }


}
