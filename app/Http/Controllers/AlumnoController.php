<?php

namespace App\Http\Controllers;

use App\Models\Periodo;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Pago;
use App\Models\Horario;
use App\Models\Asistencia; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function index(){
        return view('alumno.dashboard');
    }

    public function horario(){
        $idAlumno = Auth::user()->id_usuario;

        $horarios = Horario::select('horarios.*', 'cursos.nombre_curso', 'usuarios.nombres as docente')
            ->join('cursos', 'horarios.id_curso', '=', 'cursos.id_curso')
            ->join('asignacion_docente', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
            ->join('docentes', 'docentes.id_docente', '=', 'asignacion_docente.id_docente')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'docentes.id_usuario')
            ->get();

        return view('alumno.horario', compact('horarios'));
    }

    public function cursos(Request $request){
        $cursos = Curso::select('cursos.id_curso', 'cursos.nombre_curso', 'usuarios.nombres as docente')
            ->join('asignacion_docente', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
            ->join('docentes', 'docentes.id_docente', '=', 'asignacion_docente.id_docente')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'docentes.id_usuario')
            ->get();

        $periodos = Periodo::all(); // ✅ sin el prefijo "\App\Models\"

        return view('alumno.cursos', compact('cursos', 'periodos'));
    }
      
    public function verCurso($id){
        $curso = DB::table('cursos')
        ->leftJoin('asignacion_docente', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
        ->leftJoin('docentes', 'docentes.id_docente', '=', 'asignacion_docente.id_docente')
        ->leftJoin('usuarios', 'usuarios.id_usuario', '=', 'docentes.id_usuario')
        ->select(
            'cursos.*',
            DB::raw("CONCAT(usuarios.nombres, ' ', usuarios.apellidos) as docente")
        )
        ->where('cursos.id_curso', $id)
        ->first();
    
        $alumnos = DB::table('alumno_curso')
            ->join('alumnos', 'alumno_curso.id_alumno', '=', 'alumnos.id_alumno')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'alumnos.id_usuario')
            ->where('alumno_curso.id_curso', $id)
            ->select('usuarios.nombres', 'usuarios.apellidos', 'alumnos.id_alumno')
            ->get();

        $temas = DB::table('temas')
            ->where('id_curso', $id)
            ->orderByRaw('CAST(semana AS UNSIGNED) ASC')
            ->get();

        $materiales = DB::table('materiales_apoyo')
            ->where('id_curso', $id)
            ->orderBy('semana', 'asc')
            ->select('id_material', 'semana', 'titulo', 'archivo_pdf')
            ->get();

        
        // 🔹 Obtener el enlace del curso (usando el nombre del curso)
        $enlaceClase = DB::table('enlaces_clases')
        ->where('id_curso', $curso->id_curso)
        ->value('enlace'); // solo devuelve la columna "enlace"

        $usuarioId = Auth::id();
        $alumno = Alumno::where('id_usuario', $usuarioId)->first();

        $asistencias = collect();
        if ($alumno) {
            $asistencias = Asistencia::where('id_curso', $id)
                ->where('id_alumno', $alumno->id_alumno)
                ->orderBy('fecha', 'desc')
                ->get();
        }

        return view('alumno.curso_detalle', compact(
            'curso', 'alumnos', 'temas', 'materiales', 'enlaceClase', 'asistencias'
        ));
    }
//📄 Mostrar información del perfil (para el modal)
    public function perfil(){
        $usuario = Auth::user();

        $alumno = Alumno::with(['usuario', 'carrera', 'modalidad'])
            ->where('id_usuario', $usuario->id_usuario)
            ->first();

        return view('alumno.perfil', compact('alumno'));
    }

    public function editar(){
        $alumno = auth()->user()->alumno;
        return view('alumno.editar_perfil', compact('alumno'));
    }


        // 💾 Guardar los cambios del perfil
    public function actualizar(Request $request)
    {
        $usuario = Auth::user();

        // Validar campos
        $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'edad' => 'nullable|integer|min:15|max:100',
            'carrera' => 'nullable|string|max:100',
            'bloque' => 'nullable|string|max:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Actualizar datos
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->edad = $request->edad;
        $usuario->carrera = $request->carrera;
        $usuario->bloque = $request->bloque;

        // Si sube una nueva foto
        if ($request->hasFile('foto')) {
            // Borrar la anterior si no es la predeterminada
            if ($usuario->foto && $usuario->foto !== 'default.jpg') {
                Storage::delete('public/fotos_perfil/' . $usuario->foto);
            }

            // Guardar la nueva
            $path = $request->file('foto')->store('public/fotos_perfil');
            $usuario->foto = basename($path);
        }

        $usuario->save();

        return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
    }


    public function pagos(){
        $alumno = auth()->user()->alumno; // relación usuario → alumno

        $pagos = DB::table('pagos')
            ->where('id_alumno', $alumno->id_alumno)
            ->get();

        $totalAdeudado = $pagos->where('estado', 'Pendiente')->sum('monto');

        return view('alumno.pagos', compact('pagos', 'totalAdeudado'));
    }


    public function resultados(){
        $usuario = Auth::user();

        // Obtener el alumno vinculado al usuario
        $alumno = DB::table('alumnos')
            ->where('id_usuario', $usuario->id_usuario)
            ->first();

        if (!$alumno) {
            return back()->with('error', 'No se encontró el registro del alumno.');
        }

        // Traer los resultados del alumno desde la tabla notas
        $resultados = DB::table('notas')
            ->join('cursos', 'cursos.id_curso', '=', 'notas.id_curso')
            ->where('notas.id_alumno', $alumno->id_alumno)
            ->select(
                'cursos.nombre_curso AS titulo',
                'notas.evaluacion',
                'notas.puntaje AS puntaje_total',
                'notas.fecha_registro'
            )
            ->orderBy('notas.fecha_registro', 'desc')
            ->get();

        return view('alumno.resultados', compact('resultados'));
    }


}