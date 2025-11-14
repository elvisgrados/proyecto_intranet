<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutor;
use App\Models\Asistencia;
use App\Models\Horario;
use App\Models\Periodo; 
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TutorController extends Controller{
    public function index(Request $request){
        // Tutor autenticado
        $usuario = Auth::user();
        $tutor = Tutor::where('id_usuario', $usuario->id_usuario)->first();

        if (!$tutor) {
            return back()->with('error', 'No se encontró un tutor asociado a este usuario.');
        }

        // Alumnos asignados al tutor
        $alumnos = DB::table('asignacion_tutor')
            ->join('alumnos', 'asignacion_tutor.id_alumno', '=', 'alumnos.id_alumno')
            ->join('usuarios', 'alumnos.id_usuario', '=', 'usuarios.id_usuario')
            ->select('alumnos.id_alumno', 'usuarios.nombres', 'usuarios.apellidos')
            ->orderBy('usuarios.apellidos')
            ->get();

        if ($alumnos->isEmpty()) {
            return back()->with('error', 'No tienes alumnos asignados.');
        }

        // Fecha actual o seleccionada
        $fecha = $request->fecha ?? date('Y-m-d');

        // Obtener asistencia previa (si existe)
        $asistencias = DB::table('asistencias')
            ->whereIn('id_alumno', $alumnos->pluck('id_alumno'))
            ->where('fecha', $fecha)
            ->get()
            ->keyBy('id_alumno');

        return view('tutor.asistencia', compact('alumnos', 'fecha', 'asistencias'));
    }

    public function store(Request $request){
        foreach ($request->alumnos as $idAlumno => $data) {
            $estado = $data['estado'];

            // Conversión a puntaje
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
                    'fecha' => $request->fecha,
                ],
                [
                    'estado' => $estado,
                    'observacion' => $data['observacion'] ?? null,
                    'puntaje' => $puntaje,
                    'id_curso' => $data['id_curso'] ?? 1, // Opcional si el tutor no marca por curso
                ]
            );
        }

        return back()->with('success', '✅ Asistencia registrada correctamente por el tutor.');
    }

    public function Horario(Request $request){
        try {
            //  Obtener periodo activo
            $periodo = DB::table('periodos')->where('estado', 1)->first();

            if (!$periodo) {
                return back()->with('error', 'No hay período activo.');
            }

            // 2️⃣ Obtener lista de docentes
            $docentes = DB::table('docentes')
                ->join('usuarios', 'docentes.id_usuario', '=', 'usuarios.id_usuario')
                ->select('docentes.id_docente', DB::raw("CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre"))
                ->orderBy('usuarios.nombres')
                ->get();

            // 3️⃣ Docente seleccionado
            $idDocente = $request->input('docente');

            // 4️⃣ Consulta de horarios
            $query = DB::table('horarios')
                ->join('cursos', 'horarios.id_curso', '=', 'cursos.id_curso')
                ->leftJoin('asignacion_docente', 'cursos.id_curso', '=', 'asignacion_docente.id_curso')
                ->leftJoin('docentes', 'asignacion_docente.id_docente', '=', 'docentes.id_docente')
                ->leftJoin('usuarios', 'docentes.id_usuario', '=', 'usuarios.id_usuario')
                ->join('alumno_curso', 'cursos.id_curso', '=', 'alumno_curso.id_curso')
                ->where('alumno_curso.id_periodo', $periodo->id_periodo)
                ->select(
                    'horarios.dia',
                    'horarios.hora_inicio',
                    'horarios.hora_fin',
                    'horarios.aula',
                    'cursos.nombre_curso AS curso',
                    DB::raw("CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS docente")
                )
                ->distinct()
                ->orderBy('horarios.dia')
                ->orderBy('horarios.hora_inicio');

            // 5️⃣ Filtrar por docente si se seleccionó
            if (!empty($idDocente)) {
                $query->where('docentes.id_docente', $idDocente);
            }

            $horarios = $query->get();

            // 6️⃣ Enviar todo a la vista
            return view('tutor.horario', compact('periodo', 'horarios', 'docentes', 'idDocente'));

        } catch (\Exception $e) {
            // Si ocurre un error, mostrarlo para depuración
            dd('Error en Horario: ' . $e->getMessage());
        }
    }

    // 🔹 Muestra la lista de alumnos y evaluaciones
    public function evaluacion(){
        $usuario = Auth::user();
        $tutor = DB::table('tutores')->where('id_usuario', $usuario->id_usuario)->first();

        if (!$tutor) {
            abort(403, 'No se encontró un tutor vinculado a esta cuenta');
        }

        // Traer alumnos asignados a este tutor
        $alumnos = DB::table('asignacion_tutor')
            ->join('alumnos', 'asignacion_tutor.id_alumno', '=', 'alumnos.id_alumno')
            ->join('usuarios', 'alumnos.id_usuario', '=', 'usuarios.id_usuario')
            ->select('alumnos.id_alumno', 'usuarios.nombres', 'usuarios.apellidos')
            ->where('asignacion_tutor.id_tutor', $tutor->id_tutor)
            ->get();

        // Traer todas las evaluaciones existentes
        $evaluaciones = DB::table('evaluaciones')
            ->orderBy('fecha', 'asc')
            ->get();

        // Traer notas registradas
        $notas = DB::table('notas')
            ->whereIn('id_alumno', $alumnos->pluck('id_alumno'))
            ->get();

        return view('tutor.evaluaciones', compact('alumnos', 'evaluaciones', 'notas'));
    }

    // 🔹 Guardar puntaje
    public function guardarNota(Request $request){
        $request->validate([
            'id_alumno' => 'required|integer',
            'id_evaluacion' => 'required|integer',
            'puntaje' => 'required|numeric|min:0|max:120',
        ]);

        $notaExistente = DB::table('notas')
            ->where('id_alumno', $request->id_alumno)
            ->where('evaluacion', $request->id_evaluacion)
            ->first();

        if ($notaExistente) {
            DB::table('notas')
                ->where('id_nota', $notaExistente->id_nota)
                ->update([
                    'puntaje' => $request->puntaje,
                    'fecha_registro' => now(),
                ]);
        } else {
            DB::table('notas')->insert([
                'id_alumno' => $request->id_alumno,
                'id_curso' => 0,
                'evaluacion' => $request->id_evaluacion,
                'puntaje' => $request->puntaje,
                'fecha_registro' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    // 🔹 Crear nueva evaluación
    public function crearEvaluacion(Request $request){
        $request->validate([
            'titulo' => 'required|string|max:150',
            'tipo' => 'required|in:semanal,simulacro',
            'semana' => 'nullable|integer|min:1|max:20',
            'fecha' => 'nullable|date',
        ]);

        DB::table('evaluaciones')->insert([
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'semana' => $request->semana,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('tutor.evaluaciones')->with('success', 'Evaluación creada correctamente');
    }

    // Mostrar informe general de alumnos asignados al tutor actual.
    public function informes(){
        $tutor = Auth::user();

        // Buscar ID del tutor logueado
        $tutorId = DB::table('tutores')
            ->where('id_usuario', $tutor->id_usuario)
            ->value('id_tutor');

        // Obtener todos los periodos
        $periodos = DB::table('periodos')->get();
        $periodoActual = DB::table('periodos')->where('estado', 1)->first();
        $periodoSeleccionado = $periodoActual ? $periodoActual->id_periodo : null;

        // Generar informe automático si hay periodo actual
        if ($periodoSeleccionado) {
            $req = new Request(['periodo' => $periodoSeleccionado]);
            return $this->filtrar($req);
        }

        return view('tutor.informes', compact('periodos', 'periodoSeleccionado'));
    }

    //Generar informe general del tutor actual según periodo.
    public function filtrar(Request $request){
        $tutor = Auth::user();
        $tutorId = DB::table('tutores')->where('id_usuario', $tutor->id_usuario)->value('id_tutor');
        $periodoSeleccionado = $request->input('periodo');

        $periodos = DB::table('periodos')->get();

        // Alumnos asignados al tutor
        $alumnos = DB::table('asignacion_tutor as at')
            ->join('alumnos as a', 'at.id_alumno', '=', 'a.id_alumno')
            ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
            ->where('at.id_tutor', $tutorId)
            ->select('a.id_alumno', 'u.nombres', 'u.apellidos')
            ->get();

        $totalPromedios = 0;
        $totalAsistencias = 0;
        $totalEvaluaciones = 0;
        $alumnosEnRiesgo = 0;

        $alumnosProcesados = [];

        foreach ($alumnos as $alumno){
            // Promedio general del alumno (todas las evaluaciones)
            $promedio = DB::table('notas')
                ->where('id_alumno', $alumno->id_alumno)
                ->avg('puntaje');

            $promedio = $promedio ? round($promedio, 2) : null;

            // Asistencia global (todas las clases)
            $totalAsistenciasAlumno = DB::table('asistencias')
                ->where('id_alumno', $alumno->id_alumno)
                ->count();

            $presentes = DB::table('asistencias')
                ->where('id_alumno', $alumno->id_alumno)
                ->where('estado', 'Presente')
                ->count();

            $asistencia = $totalAsistenciasAlumno > 0
                ? round(($presentes / $totalAsistenciasAlumno) * 100, 2)
                : null;

            // Determinar estado académico
            if (is_null($promedio)) {
                $estado = 'Sin datos';
            } elseif ($promedio >= 50) {
                $estado = 'Destacada';
            } elseif ($promedio >= 25) {
                $estado = 'Regular';
            } else {
                $estado = 'En riesgo';
                $alumnosEnRiesgo++;
            }

            // Acumular para el resumen
            if (!is_null($promedio)) $totalPromedios += $promedio;
            if (!is_null($asistencia)) $totalAsistencias += $asistencia;

            // Contar evaluaciones globales
            $evaluacionesAlumno = DB::table('notas')
                ->where('id_alumno', $alumno->id_alumno)
                ->distinct('evaluacion')
                ->count('evaluacion');
            $totalEvaluaciones += $evaluacionesAlumno;

            // Añadir datos al objeto
            $alumno->promedio = $promedio;
            $alumno->asistencia = $asistencia;
            $alumno->estado = $estado;

            $alumnosProcesados[] = $alumno;
        }

        $cantidad = count($alumnosProcesados);
        $promedioGeneral = $cantidad > 0 ? round($totalPromedios / $cantidad, 2) : null;
        $asistenciaPromedio = $cantidad > 0 ? round($totalAsistencias / $cantidad, 2) : null;
        $evaluacionesRealizadas = $cantidad > 0 ? $totalEvaluaciones : 0;

        return view('tutor.informes', [
            'periodos' => $periodos,
            'periodoSeleccionado' => $periodoSeleccionado,
            'alumnos' => $alumnosProcesados,
            'promedioGeneral' => $promedioGeneral,
            'asistenciaPromedio' => $asistenciaPromedio,
            'evaluacionesRealizadas' => $evaluacionesRealizadas,
            'alumnosEnRiesgo' => $alumnosEnRiesgo
        ]);
    }

    public function detalleAlumno($id){
        // 🔹 Obtener datos del alumno con su usuario
        $alumno = DB::table('alumnos as a')
            ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
            ->where('a.id_alumno', $id)
            ->select(
                'a.id_alumno',
                'a.id_usuario',
                'u.nombres',
                'u.apellidos',
                'u.email',
                'u.dni',
                'u.telefono'
            )
            ->first();

        if (! $alumno) {
            abort(404, 'Alumno no encontrado');
        }

        // 🔹 Consultar notas ordenadas (todas las evaluaciones globales)
        $notas = DB::table('notas')
            ->where('id_alumno', $id)
            ->orderBy('fecha_registro', 'desc')
            ->get();

        // 🔹 Consultar asistencias ordenadas
        $asistencias = DB::table('asistencias')
            ->where('id_alumno', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        // 🔹 (opcional) Resumen promedio del alumno
        $promedio = DB::table('notas')
            ->where('id_alumno', $id)
            ->avg('puntaje');

        $promedio = $promedio ? round($promedio, 2) : null;

        return view('tutor.informes_alumno_detalle', compact('alumno', 'notas', 'asistencias', 'promedio'));
    }

    public function descargarPDF($id){
        // 🔹 Obtener alumno y su información básica
        $alumno = DB::table('alumnos as a')
            ->join('usuarios as u', 'a.id_usuario', '=', 'u.id_usuario')
            ->where('a.id_alumno', $id)
            ->select(
                'a.id_alumno',
                'u.nombres',
                'u.apellidos',
                'u.email',
                'u.dni'
            )
            ->first();

        if (! $alumno) {
            abort(404, 'Alumno no encontrado');
        }

        // 🔹 Obtener notas y asistencias
        $notas = DB::table('notas')
            ->where('id_alumno', $id)
            ->orderBy('fecha_registro', 'desc')
            ->get();

        $asistencias = DB::table('asistencias')
            ->where('id_alumno', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        // 🔹 Generar PDF
        $pdf = Pdf::loadView('tutor.detalle_alumno_pdf', compact('alumno', 'notas', 'asistencias'))
            ->setPaper('a4', 'portrait');

        $nombreArchivo = 'detalle_alumno_' . str_replace(' ', '_', $alumno->nombres) . '.pdf';

        return $pdf->download($nombreArchivo);
    }


    //Perfil
    public function perfil(){
        // Usuario autenticado
        $usuario = Auth::user();

        // Obtener los datos del tutor asociado al usuario
        $tutor = DB::table('tutores')
            ->where('id_usuario', $usuario->id_usuario)
            ->first();

        // Retornar vista del perfil
        return view('tutor.perfil', compact('usuario', 'tutor'));
    }

    public function perfiltutor(){
        $usuario = Auth::user();

        // Obtener datos del tutor asociado al usuario
        $tutor = DB::table('tutores')
            ->where('id_usuario', $usuario->id_usuario)
            ->first();

        // Retornar vista de configuración
        return view('tutor.configuracion', compact('usuario', 'tutor'));
    }

    public function actualizar(Request $request){
        // Usuario autenticado (tutor)
        $usuario = Auth::user();

        // Validación de datos
        $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'required|email|max:100|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'telefono'  => 'nullable|string|max:20',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        // Guardar cambios básicos del usuario
        DB::table('usuarios')
            ->where('id_usuario', $usuario->id_usuario)
            ->update([
                'nombres'   => $request->nombres,
                'apellidos' => $request->apellidos,
                'email'     => $request->email,
                'telefono'  => $request->telefono,
            ]);

        // Manejar foto (si se sube una nueva)
        if ($request->hasFile('foto')) {
            $rutaActual = DB::table('usuarios')->where('id_usuario', $usuario->id_usuario)->value('foto');

            // Eliminar la anterior si existe
            if ($rutaActual && file_exists(public_path($rutaActual))) {
                @unlink(public_path($rutaActual));
            }

            // Guardar nueva
            $nombreArchivo = 'tutor_' . $usuario->id_usuario . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/fotos'), $nombreArchivo);
            $nuevaRuta = 'uploads/fotos/' . $nombreArchivo;

            DB::table('usuarios')
                ->where('id_usuario', $usuario->id_usuario)
                ->update(['foto' => $nuevaRuta]);
        }

        // Refrescar sesión
        $usuarioActualizado = DB::table('usuarios')->where('id_usuario', $usuario->id_usuario)->first();
        Auth::loginUsingId($usuarioActualizado->id_usuario);

        return redirect()->back()->with('success', 'Configuración del tutor actualizada correctamente.');
    }
}



