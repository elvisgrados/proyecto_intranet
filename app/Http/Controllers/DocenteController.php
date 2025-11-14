<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;
use App\Models\AsignacionDocente;
use App\Models\Curso;
use App\Models\Horario;
use App\Models\Periodo;


class DocenteController extends Controller{
    public function index(){
        
        $docente = Auth::user();

        // Total de cursos activos del docente
        $cursos = DB::table('curso_docente')
            ->join('cursos', 'curso_docente.id_curso', '=', 'cursos.id')
            ->where('curso_docente.id_docente', $docente->id)
            ->where('cursos.estado', 'ACTIVO')
            ->select('cursos.id', 'cursos.nombre')
            ->get();

        // Alumnos totales (de todos sus cursos)
        $alumnos = DB::table('matriculas')
            ->join('alumnos', 'matriculas.id_alumno', '=', 'alumnos.id')
            ->whereIn('matriculas.id_curso', $cursos->pluck('id'))
            ->count();

        // Clases de hoy (según horario)
        $dia = date('N'); // 1 = Lunes ... 7 = Domingo
        $clasesHoy = DB::table('horarios')
            ->join('cursos', 'horarios.id_curso', '=', 'cursos.id')
            ->whereIn('horarios.id_curso', $cursos->pluck('id'))
            ->where('horarios.dia_semana', $dia)
            ->select('cursos.nombre', 'horarios.hora_inicio', 'horarios.hora_fin')
            ->get();

        // Evaluaciones pendientes de calificar
        $evaluacionesPendientes = DB::table('evaluaciones')
            ->join('cursos', 'evaluaciones.id_curso', '=', 'cursos.id')
            ->whereIn('evaluaciones.id_curso', $cursos->pluck('id'))
            ->whereNull('evaluaciones.fecha_calificacion')
            ->count();

        // Mensajes administrativos nuevos
        $mensajes = DB::table('mensajes')
            ->where('para', 'DOCENTE')
            ->where('leido', 0)
            ->count();

        return view('docente.dashboard', [
            'docente' => $docente,
            'totalCursos' => $cursos->count(),
            'totalAlumnos' => $alumnos,
            'clasesHoy' => $clasesHoy,
            'evaluacionesPendientes' => $evaluacionesPendientes,
            'mensajes' => $mensajes
        ]);
    }

    public function vercurso(){
        $usuario = Auth::user();

        // 1. Buscar el docente asociado al usuario
        $docente = Docente::where('id_usuario', $usuario->id_usuario)->first();

        if (!$docente) {
            return redirect()->back()->with('error', 'No se encontró un perfil docente asociado a tu cuenta.');
        }

        // 2. Obtener cursos asignados al docente con horarios
        $asignaciones = AsignacionDocente::with(['curso.horarios'])
            ->where('id_docente', $docente->id_docente)
            ->get();

        // 3. Obtener lista de IDs de cursos asignados
        $cursoIds = $asignaciones->pluck('id_curso')->toArray();

        // Si no hay cursos asignados, no queremos que la consulta de pendientes falle
        if (empty($cursoIds)) {
            $pendientes = 0;
            return view('docente.cursos', compact('asignaciones', 'docente'));
        }

        return view('docente.cursos', compact('asignaciones', 'docente'));
    }

    public function verDetalleCurso($id){
        $curso = Curso::findOrFail($id);

        // cantidad de alumnos activos
        $cantidadAlumnos = DB::table('alumno_curso')
            ->join('alumnos', 'alumno_curso.id_alumno', '=', 'alumnos.id_alumno')
            ->join('usuarios', 'alumnos.id_usuario', '=', 'usuarios.id_usuario')
            ->where('alumno_curso.id_curso', $id)
            ->where('usuarios.estado', 1)
            ->count();

        // temas por semana
        $temasPorSemana = DB::table('temas')
            ->where('id_curso', $id)
            ->orderBy('semana')
            ->get()
            ->groupBy('semana');

        $materialesPorSemana = DB::table('materiales_apoyo')
            ->where('id_curso', $id)
            ->orderBy('semana')
            ->get()
            ->groupBy('semana');

        return view('docente.curso-detalle', compact(
            'curso', 'temasPorSemana', 'materialesPorSemana', 'cantidadAlumnos'
        ));

    }

    public function subirMaterialApoyo(Request $request, $id_curso){
        $request->validate([
            'titulo' => 'required|string|max:150',
            'semana' => 'required|integer|min:1',
            'archivo_pdf' => 'required|mimes:pdf|max:10240',
        ]);

        $path = $request->file('archivo_pdf')->store('materiales', 'public');

        DB::table('materiales_apoyo')->insert([
            'id_curso' => $id_curso,
            'semana' => $request->semana,
            'titulo' => $request->titulo,
            'archivo_pdf' => $path,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Material subido correctamente.');
    }

     public function resultadosGenerales(Request $request){
        // Revisar si hay notas que mencionen "general" en el campo evaluacion
        $hayGeneral = DB::table('notas')
            ->where('evaluacion', 'like', '%general%')
            ->exists();

        $query = DB::table('notas')
            ->join('alumnos', 'notas.id_alumno', '=', 'alumnos.id_alumno')
            ->join('usuarios', 'alumnos.id_usuario', '=', 'usuarios.id_usuario')
            ->select(
                'usuarios.nombres',
                'usuarios.apellidos',
                'usuarios.dni',
                'notas.puntaje',
                'notas.fecha_registro'
            );

        // Si hay evaluaciones con texto "general", filtramos por ellas
        if ($hayGeneral) {
            $query->where('notas.evaluacion', 'like', '%general%');
        }

        $resultados = $query->orderBy('notas.fecha_registro', 'desc')->get();

        return view('docente.resultados_evaluaciones', compact('resultados'));
    }

    //Horario Doncente
    public function HorarioDocente(){
        $usuario = Auth::user();

        // Obtener docente autenticado
        $docente = Docente::where('id_usuario', $usuario->id_usuario)->first();

        // Obtener cursos asignados al docente
        $cursos = \App\Models\AsignacionDocente::where('id_docente', $docente->id_docente)
                    ->join('cursos', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
                    ->select('cursos.nombre_curso')
                    ->orderBy('cursos.nombre_curso', 'asc')
                    ->get();

        if (!$docente) {
            return back()->with('error', 'No se encontró información del docente asociado.');
        }

        // Obtener los IDs de cursos asignados al docente
        $cursos1 = $docente->cursos()->pluck('cursos.id_curso');

        // Obtener los horarios junto con el nombre del curso
        $horarios = Horario::with('curso:id_curso,nombre_curso')
            ->whereIn('id_curso', $cursos1)
            ->orderByRaw("FIELD(dia, 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado')")
            ->orderBy('hora_inicio')
            ->get();

        $periodo = Periodo::where('estado', 1)->first();

        return view('docente.horario', compact('docente', 'horarios', 'cursos', 'periodo'));
    }

    public function perfil(Request $request){
        $usuario = Auth::user();

        $docente = \App\Models\Docente::where('id_usuario', $usuario->id_usuario)->first();

        // Obtener cursos asignados al docente
        $cursos = \App\Models\AsignacionDocente::where('id_docente', $docente->id_docente)
                    ->join('cursos', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
                    ->select('cursos.nombre_curso')
                    ->orderBy('cursos.nombre_curso', 'asc')
                    ->get();

        return view('docente.perfil', compact('usuario', 'docente', 'cursos'));
    }

    public function configuracion(){
        $usuario = Auth::user();

        // Obtener datos del docente asociado al usuario
        $docente = \App\Models\Docente::where('id_usuario', $usuario->id_usuario)->first();

        return view('docente.configuracion', compact('usuario', 'docente'));
    }

    public function actualizar(Request $request){
        $usuario = Auth::user();

        // Validación de datos generales (sin afectar contraseña)
        $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos'=> 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        // Actualizar datos básicos
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        // Subida de foto
        if ($request->hasFile('foto')) {
            if ($usuario->foto && file_exists(public_path($usuario->foto))) {
                unlink(public_path($usuario->foto));
            }

            $nombreArchivo = 'usuario_' . $usuario->id_usuario . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/fotos'), $nombreArchivo);
            $usuario->foto = 'uploads/fotos/' . $nombreArchivo;
        }

    

        $usuario->save();

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}

