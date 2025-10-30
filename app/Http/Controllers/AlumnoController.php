<?php

namespace App\Http\Controllers;

use App\Models\Periodo;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Pago;
use App\Models\ResultadoSimulacro;
use App\Models\Horario;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    public function index()
    {
        return view('alumno.dashboard');
    }

    public function horario()
    {
        $idAlumno = Auth::user()->id_usuario;

        $horarios = Horario::select('horarios.*', 'cursos.nombre_curso', 'usuarios.nombres as docente')
            ->join('cursos', 'horarios.id_curso', '=', 'cursos.id_curso')
            ->join('asignacion_docente', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
            ->join('docentes', 'docentes.id_docente', '=', 'asignacion_docente.id_docente')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'docentes.id_usuario')
            ->get();

        return view('alumno.horario', compact('horarios'));
    }

    public function cursos(Request $request)
    {
        $cursos = Curso::select('cursos.id_curso', 'cursos.nombre_curso', 'usuarios.nombres as docente')
            ->join('asignacion_docente', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
            ->join('docentes', 'docentes.id_docente', '=', 'asignacion_docente.id_docente')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'docentes.id_usuario')
            ->get();

        $periodos = Periodo::all(); // ✅ sin el prefijo "\App\Models\"

        return view('alumno.cursos', compact('cursos', 'periodos'));
    }
    
    public function verCurso($id)
    {
        // ✅ Obtener datos del curso y su docente asignado
        $curso = \DB::table('cursos')
            ->join('asignacion_docente', 'asignacion_docente.id_curso', '=', 'cursos.id_curso')
            ->join('docentes', 'docentes.id_docente', '=', 'asignacion_docente.id_docente')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'docentes.id_usuario')
            ->where('cursos.id_curso', $id)
            ->select(
                'cursos.id_curso',
                'cursos.nombre_curso',
                'usuarios.nombres as docente'
            )
            ->first();
    
        if (!$curso) {
            abort(404, 'Curso no encontrado');
        }
    
        // 👩‍🎓 Obtener alumnos inscritos a través de la tabla notas
        $alumnos = \DB::table('notas')
            ->join('alumnos', 'alumnos.id_alumno', '=', 'notas.id_alumno')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'alumnos.id_usuario')
            ->where('notas.id_curso', $id)
            ->select('usuarios.nombres as nombre', 'usuarios.apellidos as apellido')
            ->groupBy('usuarios.nombres', 'usuarios.apellidos')
            ->get();
    
        // 📅 Temas y ejercicios (por ahora estáticos)
        $temas = [
            ['semana' => 1, 'tema' => 'Introducción y fundamentos', 'ejercicio' => 'Cuestionario 1'],
            ['semana' => 2, 'tema' => 'Aplicaciones prácticas', 'ejercicio' => 'Ejercicios de refuerzo 2'],
            ['semana' => 3, 'tema' => 'Proyecto final', 'ejercicio' => 'Simulacro final'],
        ];
    
        // 💻 Enlace de clase y botón de examen (solo simulado)
        $enlaceClase = 'https://zoom.us/clase123';
        $enlaceExamen = route('alumno.examen', $curso->id_curso);
    
        // 📤 Enviar datos a la vista
        return view('alumno.curso_detalle', compact('curso', 'alumnos', 'temas', 'enlaceClase', 'enlaceExamen'));
    }
    

    
    // 📄 Mostrar información del perfil (para el modal)
    public function perfil()
{
    $usuario = Auth::user();

    $alumno = Alumno::with(['usuario', 'carrera', 'modalidad'])
                    ->where('id_usuario', $usuario->id_usuario)
                    ->first();

    return view('alumno.perfil', compact('alumno'));
}

public function editar()
{
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
         $alumno = auth()->user()->alumno; // relación usuario -> alumno
        $pagos = Pago::where('id_alumno', $alumno->id_alumno)->get();
        $totalAdeudado = $pagos->where('estado', 'Pendiente')->sum('monto');

        return view('alumno.pagos', compact('pagos', 'totalAdeudado'));
    }


    public function resultados()
    {
        $idAlumno = Auth::user()->id_usuario;

        $resultados = ResultadoSimulacro::select('simulacros.titulo', 'resultados_simulacro.puntaje_total', 'resultados_simulacro.fecha_registro')
            ->join('simulacros', 'simulacros.id_simulacro', '=', 'resultados_simulacro.id_simulacro')
            ->where('id_alumno', $idAlumno)
            ->get();

        return view('alumno.resultados', compact('resultados'));
    }

}