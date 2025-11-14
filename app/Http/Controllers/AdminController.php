<?php

namespace App\Http\Controllers;

use App\Exports\CarrerasExport;
use App\Exports\DocentesExport;
use App\Exports\MatriculasExport;
use App\Exports\PagosExport;
use App\Exports\ReportesExport;
use App\Exports\CursosExport;
use App\Models\Alerta;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Horario;
use App\Models\Docente;
use App\Models\AsignacionDocente;
use App\Models\Curso;
use App\Models\Matriculas;
use App\Models\PagoDocente;
use App\Models\PagoMatricula;
use App\Models\Reporte;
use App\Models\TipoUsuario;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    /* =======================
       DASHBOARD PRINCIPAL
    ======================== */
    public function index()
    {
        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCarreras = Carrera::count();

        $totalIngresos = PagoMatricula::sum('monto');
        $totalEgresos  = PagoDocente::sum('monto');

        $meses = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
            7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
        ];

        $ingresos = [];
        $egresos = [];

        for($m=1;$m<=12;$m++){
            $ingresos[] = PagoMatricula::whereMonth('created_at',$m)->where('estado','pagado')->sum('monto');
            $egresos[] = PagoDocente::whereMonth('created_at',$m)->where('estado','pagado')->sum('monto');
        }
        
        //Pie chart: matriculas por carrera
        $matriculasPorCarrera = Carrera::withCount('matriculas')->get();
        
        //Alertas y reportes recientes
        $alertas = Alerta::orderBy('fecha','desc')->take(5)->get();
        $reportes = Reporte::orderBy('fecha','desc')->take(5)->get();

        //ultimas matriculas
        $ultimasMatriculas = Matriculas::with(['alumno.usuario','carrera','turno'])
                                ->orderBy('created_at','desc')
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'totalAlumnos','totalDocentes','totalCarreras',
            'totalIngresos','totalEgresos',
            'meses','ingresos','egresos',
            'matriculasPorCarrera','alertas','reportes','ultimasMatriculas'
        ));
    }

    /* =======================
       MATRÍCULAS CRUD
    ======================== */
    public function matriculas()
    {

        $alumnos = Alumno::with('usuario')->orderBy('id_alumno','asc')->get();
        $turnos = Turno::all();
        $carreras = Carrera::all();
        $horarios = Horario::all();

        $matriculas = Matriculas::with(['alumno.usuario','turno','carrera','horario'])
                        ->orderBy('created_at','desc')
                        ->get();

        $totalMatriculas = Matriculas::count();
        $totalAlumnos = Alumno::count();
        $tipos = TipoUsuario::whereIn('id_tipo',[2,3])->get();

        $matriculasPorMes = Matriculas::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )->groupBy(DB::raw('MONTH(created_at)'))
         ->pluck('total','mes')
         ->toArray();

        $meses = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
            7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
        ];

        $labels = [];
        $data = [];
        for($m=1;$m<=12;$m++){
            $labels[] = $meses[$m];
            $data[] = $matriculasPorMes[$m] ?? 0;
        }    

        return view('admin.matricula', compact(
            'matriculas','alumnos','turnos','carreras','horarios',
            'labels','data', 'totalMatriculas', 'totalAlumnos', 'tipos'
        ));
    }

    public function agregarMatricula(Request $request)
    {
        $request->validate([
            'nombres'=>'required|string|max:255',
            'apellidos'=>'required|string|max:255',
            'dni'=>'required|string|max:20|unique:usuarios,dni',
            'email'=>'required|email|max:255|unique:usuarios,email',
            'telefono'=>'nullable|string|max:20',
            'colegio_procedencia'=>'required|string|max:255',
            'id_turno'=>'required|exists:turnos,id_turno',
            'id_carrera'=>'required|exists:carreras,id_carrera',
            'id_horario'=>'nullable|exists:horarios,id_horario',
            'fecha_matricula'=>'nullable|date'
        ]);

        $usuario = Usuario::create([
            'id_tipo'=>3,
            'nombres'=>$request->nombres,
            'apellidos'=>$request->apellidos,
            'dni'=>$request->dni,
            'email'=>$request->email,
            'telefono'=>$request->telefono,
            'password'=>bcrypt($request->password ?? '123456')
        ]);

        $alumno = Alumno::create(['id_usuario'=>$usuario->id_usuario]);

        Matriculas::create([
            'id_alumno'=>$alumno->id_alumno,
            'colegio_procedencia'=>$request->colegio_procedencia,
            'id_turno'=>$request->id_turno,
            'id_carrera'=>$request->id_carrera,
            'id_horario'=>$request->id_horario,
            'fecha_matricula'=>$request->fecha_matricula ?? now(),
            'estado'=>$request->estado ?? 'activa'
        ]);

        return redirect()->back()->with('success','Matrícula registrada correctamente.');
    }

    public function editarMatricula(Request $request,$id)
    {
        $matricula = Matriculas::findOrFail($id);
        $usuario = $matricula->alumno->usuario;

        $usuario->update([
            'nombres'=>$request->nombre,
            'dni'=>$request->dni,
            'email'=>$request->correo,
            'telefono'=>$request->telefono
        ]);

        $matricula->update([
            'colegio_procedencia'=>$request->colegio,
            'id_turno'=>$request->id_turno,
            'id_carrera'=>$request->id_carrera,
            'id_horario'=>$request->id_horario
        ]);

        return response()->json([
            'status'=>'success',
            'matricula'=>$matricula->load(['alumno.usuario','turno','carrera','horario'])
        ]);
    }

    public function eliminarMatricula($id)
    {
        $matricula = Matriculas::findOrFail($id);
        $usuario = $matricula->alumno->usuario;

        $matricula->delete();
        $matricula->alumno->delete();
        $usuario->delete();

        return response()->json(['status'=>'success']);
    }

    /* =======================
       CARRERAS CRUD
    ======================== */
    public function carrera()
    {
        $carreras = Carrera::with('cursos')->get();
        $cursos = Curso::all();
        return view('admin.carrera', compact('carreras','cursos'));
    }

    public function agregarCarrera(Request $request)
    {
        $request->validate([
            'nombre_carrera'=>'required|string|max:255',
            'area'=>'required|string|max:255',
            'estado'=>'required|in:activa,inactiva',
            'cursos'=>'array'
        ]);

        $carrera = Carrera::create($request->only(['nombre_carrera','area','estado']));

        if($request->has('cursos')){
            $carrera->cursos()->sync($request->cursos);
        }

        return redirect()->back()->with('success','Carrera registrada correctamente.');
    }

    public function actualizarCarrera(Request $request,$id)
    {
        $request->validate([
            'nombre_carrera'=>'required|string|max:255',
            'area'=>'required|string|max:255',
            'estado'=>'required|in:activa,inactiva',
            'cursos'=>'array'
        ]);

        $carrera = Carrera::findOrFail($id);
        $carrera->update($request->only(['nombre_carrera','area','estado']));
        $carrera->cursos()->sync($request->cursos ?? []);
        return redirect()->back()->with('success','Carrera actualizada correctamente.');
    }

    public function eliminarCarrera($id)
    {
        $carrera = Carrera::findOrFail($id);
        $carrera->cursos()->detach();
        $carrera->delete();
        return redirect()->back()->with('success','Carrera eliminada correctamente.');
    }

    // =======================
//       CURSOS CRUD
// =======================
public function agregarCurso(Request $request)
{
    $request->validate([
        'nombre_curso'=>'required|string|max:255',
        'categoria'=>'required|string|max:255',
        'duracion'=>'required|string|max:100',
        'estado'=>'required|in:activa,inactiva',
    ]);

    Curso::create($request->only(['nombre_curso','categoria','duracion','estado']));
    return redirect()->back()->with('success','Curso registrado correctamente.');
}

public function actualizarCurso(Request $request, $id)
{
    $request->validate([
        'nombre_curso'=>'required|string|max:255',
        'categoria'=>'required|string|max:255',
        'duracion'=>'required|string|max:100',
        'estado'=>'required|in:activa,inactiva',
    ]);

    $curso = Curso::findOrFail($id);
    $curso->update($request->only(['nombre_curso','categoria','duracion','estado']));
    return redirect()->back()->with('success','Curso actualizado correctamente.');
}

public function eliminarCurso($id)
{
    $curso = Curso::findOrFail($id);
    $curso->delete();
    return redirect()->back()->with('success','Curso eliminado correctamente.');
}

/* =======================
       DOCENTES CRUD
   ======================= */
public function docentes()
{
    // Carga los docentes con su usuario y sus cursos relacionados
    $docentes = Docente::with(['usuario', 'cursos'])->get();
    $cursos = Curso::all();

    return view('admin.docentes', compact('docentes', 'cursos'));
}

public function agregarDocente(Request $request)
{
    $request->validate([
        'dni' => 'required|string|max:20|unique:usuarios,dni',
        'nombre' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'email' => 'required|email|unique:usuarios,email',
        'telefono' => 'required|string|max:20',
        'estado' => 'required|in:Activo,Inactivo',
        'cursos' => 'array'
    ]);

    // Crear el usuario (tabla usuarios)
    $usuario = Usuario::create([
        'dni' => $request->dni,
        'nombres' => $request->nombre,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
        'telefono' => $request->telefono,
        'password' => bcrypt('123456'), // Contraseña por defecto
        'id_tipo' => 2, // tipo 2 = docente
        'estado' => $request->estado === 'Activo' ? 1 : 0
    ]);

    // Crear el docente (tabla docentes)
    $docente = Docente::create([
        'id_usuario' => $usuario->id_usuario,
        'estado' => $request->estado
    ]);

    // Asociar cursos seleccionados (tabla curso_docente)
    if ($request->has('cursos')) {
        $docente->cursos()->sync($request->cursos);
    }

    return redirect()->back()->with('success', 'Docente registrado correctamente.');
}




public function actualizarDocente(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email',
        'telefono' => 'required|string|max:20',
        'estado' => 'required|in:Activo,Inactivo',
        'cursos' => 'array'
    ]);

    $docente = Docente::findOrFail($id);
    $usuario = $docente->usuario;

    $usuario->update([
        'nombres' => $request->nombre,
        'email' => $request->email,
        'telefono' => $request->telefono,
        'estado' => $request->estado === 'Activo' ? 1 : 0
    ]);

    $docente->update([
        'estado' => $request->estado
    ]);

    $docente->cursos()->sync($request->cursos ?? []);

    return redirect()->back()->with('success', 'Docente actualizado correctamente.');
}

public function eliminarDocente($id)
{
    $docente = Docente::findOrFail($id);
    $docente->cursos()->detach();
    $docente->delete();
    $docente->usuario->delete();

    return redirect()->back()->with('success', 'Docente eliminado correctamente.');
}

/* =======================
       HORARIOS CRUD
   ======================== */

public function horarios()
{
    $horarios = Horario::with(['curso', 'docente.usuario', 'turno'])->get();
    $turnos = Turno::all();
    $cursos = Curso::all();
    $docentes = Docente::with('usuario')->get();

    return view('admin.horarios', compact('horarios', 'turnos', 'cursos', 'docentes'));
}

public function agregarHorario(Request $request)
{
    $request->validate([
        'id_curso' => 'required|exists:cursos,id_curso',
        'id_docente' => 'required|exists:docentes,id_docente',
        'id_turno' => 'required|exists:turnos,id_turno',
        'dia' => 'required|string|max:50',
        'hora_inicio' => 'required',
        'hora_fin' => 'required',
        'aula' => 'required|string|max:50',
        'estado' => 'required|in:Activo,Inactivo',
    ]);

    Horario::create([
        'id_curso' => $request->id_curso,
        'id_docente' => $request->id_docente,
        'dia' => $request->dia,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
        'aula' => $request->aula,
        'estado' => $request->estado,
    ]);

    $turno = Turno::findOrFail($request->id_turno);


    return redirect()->back()->with('success', 'Horario registrado correctamente.');
}

public function eliminarHorario($id)
{
    $horario = Horario::findOrFail($id);
    $horario->delete();

    return redirect()->back()->with('success', 'Horario eliminado correctamente.');
}



    /* =======================
       EXPORTACIONES
    ======================== */
    public function exportMatriculasExcel(){ return Excel::download(new MatriculasExport(),'matriculas.xlsx'); }
    public function exportDocentesExcel(){ return Excel::download(new DocentesExport(),'docentes.xlsx'); }
    public function exportCarrerasExcel(){ return Excel::download(new CarrerasExport(),'carreras.xlsx'); }
    public function exportCursosExcel(){ return Excel::download(new CursosExport(),'cursos.xlsx'); }
    public function exportPagosExcel(){ return Excel::download(new PagosExport(),'pagos.xlsx'); }
    public function exportReportesExcel(){ return Excel::download(new ReportesExport(),'reportes_general.xlsx'); }

    /* =======================
       SECCIONES EXTRA (Preparadas)
    ======================== */
    public function pagos(){ return view('admin.pagos'); }
    public function reportes(){ return view('admin.reportes'); }
    public function alertas(){ return view('admin.alertas'); }

    /* =======================
       CONFIGURACIÓN / PERFIL
    ======================== */
    public function configuracion()
    {
        $usuario = auth()->user();
        return view('admin.configuracion',compact('usuario'));
    }

    public function actualizarPerfil(Request $request)
    {
        $usuario = Usuario::findOrFail(auth()->id());

        $request->validate([
            'nombres'=>'required|string|max:255',
            'apellidos'=>'required|string|max:255',
            'dni'=>'required|string|max:20|unique:usuarios,dni,'.$usuario->id_usuario.',id_usuario',
            'email'=>'required|email|max:255|unique:usuarios,email,'.$usuario->id_usuario.',id_usuario',
            'telefono'=>'nullable|string|max:20'
        ]);

        $usuario->update($request->only(['nombres','apellidos','dni','email','telefono']));
        return redirect()->back()->with('success','Perfil actualizado correctamente.');
    }

    public function actualizarPassword(Request $request)
    {
        $usuario = Usuario::findOrFail(auth()->id());

        $request->validate([
            'current_password'=>'required',
            'new_password'=>'required|confirmed|min:6'
        ]);

        if(!Hash::check($request->current_password,$usuario->password)){
            return redirect()->back()->withErrors(['current_password'=>'La contraseña actual es incorrecta.']);
        }

        $usuario->update(['password'=>Hash::make($request->new_password)]);
        return redirect()->back()->with('success','Contraseña actualizada correctamente.');
    }
}
