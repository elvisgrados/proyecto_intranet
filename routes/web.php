<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas web de la aplicación.
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/matricula', [AdminController::class, 'matriculas'])->name('admin.matricula');
    Route::get('/admin/carrera', [AdminController::class, 'carrera'])->name('admin.carrera');
    Route::get('/admin/docentes', [AdminController::class, 'docentes'])->name('admin.docentes');
    Route::get('/admin/horarios', [AdminController::class, 'horarios'])->name('admin.horarios');
    Route::get('/admin/alertas', [AdminController::class, 'alertas'])->name('admin.alertas');
    Route::get('/admin/pagos', [AdminController::class, 'pagos'])->name('admin.pagos');
    Route::get('/admin/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');

    // Configuración
    Route::get('/admin/configuracion', [AdminController::class,'configuracion'])->name('admin.configuracion');
    Route::post('/admin/configuracion/update', [AdminController::class,'actualizarPerfil'])->name('admin.configuracion.update');
    Route::post('/admin/configuracion/password', [AdminController::class,'actualizarPassword'])->name('admin.configuracion.password');

    // Matrículas
    Route::post('/admin/matriculas/agregar', [AdminController::class, 'agregarMatricula'])->name('admin.matricula.agregar');
    Route::post('/admin/matriculas/editar/{id}', [AdminController::class, 'editarMatricula'])->name('admin.matricula.editar');
    Route::get('/admin/matriculas/actualizar/{id}', [AdminController::class, 'actualizarMatricula'])->name('admin.matricula.actualizar');
    Route::delete('/admin/matriculas/eliminar/{id}', [AdminController::class, 'eliminarMatricula'])->name('admin.matricula.eliminar');

    //carreras
    Route::post('/admin/carreras/agregar', [AdminController::class, 'agregarCarrera'])->name('admin.carreras.agregar');
    Route::get('/admin/carreras/editar/{id}', [AdminController::class, 'editarCarrera'])->name('admin.carreras.editar');
    Route::delete('/admin/carreras/eliminar/{id}', [AdminController::class, 'eliminarCarrera'])->name('admin.carreras.eliminar');
    Route::get('/admin/carreras/actualizar/{id}', [AdminController::class, 'actualizarCarrera'])->name('admin.carreras.actualizar');

    //cursos
    Route::post('/admin/cursos/agregar', [AdminController::class, 'agregarCurso'])->name('admin.cursos.agregar');
    Route::get('/admin/cursos/editar/{id}', [AdminController::class, 'editarCurso'])->name('admin.cursos.editar');
    Route::delete('/admin/cursos/eliminar/{id}', [AdminController::class, 'eliminarCurso'])->name('admin.cursos.eliminar');
    Route::get('/admin/cursos/actualizar/{id}', [AdminController::class, 'actualizarCurso'])->name('admin.cursos.actualizar');

    //docentes
    Route::post('/admin/docentes/agregar', [AdminController::class, 'agregarDocente'])->name('admin.docentes.agregar');
    Route::get('/admin/docentes/editar/{id}', [AdminController::class, 'editarDocente'])->name('admin.docentes.editar');
    Route::delete('/admin/docentes/eliminar/{id}', [AdminController::class, 'eliminarDocente'])->name('admin.docentes.eliminar');
    Route::get('/admin/docentes/actualizar/{id}', [AdminController::class, 'actualizarDocente'])->name('admin.docentes.actualizar');    
    
    //horarios
    Route::post('/admin/horarios/agregar', [AdminController::class, 'agregarHorario'])->name('admin.horarios.agregar');
    Route::get('/admin/horarios/editar/{id}', [AdminController::class, 'editarHorario'])->name('admin.horarios.editar');
    Route::delete('/admin/horarios/eliminar/{id}', [AdminController::class , 'eliminarHorario'])->name('admin.horarios.eliminar');
    Route::get('/admin/horarios/actualizar/{id}', [AdminController::class, 'actualizarHorario'])->name('admin.horarios.actualizar');
    
    // Exportaciones
    Route::prefix('admin')->group(function () {
        Route::get('matriculas/export/excel', [AdminController::class, 'exportMatriculasExcel'])->name('admin.matriculas.excel');
        Route::get('docentes/export/excel', [AdminController::class, 'exportDocentesExcel'])->name('admin.docentes.excel');
        Route::get('carreras/export/excel', [AdminController::class, 'exportCarrerasExcel'])->name('admin.carreras.excel');
        Route::get('pagos/export/excel', [AdminController::class, 'exportPagosExcel'])->name('admin.pagos.excel');
        Route::get('reportes/export/excel', [AdminController::class, 'exportReportesExcel'])->name('admin.reportes.excel');
        Route::get('cursos/export/excel', [AdminController::class, 'exportCursosExcel'])->name('admin.cursos.excel');
    });
});

// Middleware docente
Route::middleware(['auth', 'docente'])->group(function () {

    // Dashboard docente
    Route::get('/docente', [DocenteController::class, 'index'])->name('docente.dashboard');
    Route::get('/docente/horario', [DocenteController::class, 'HorarioDocente'])->name('docente.horario');

    // Evaluaciones
    Route::get('/docente/resultados_evaluaciones', [DocenteController::class, 'resultadosGenerales'])->name('evaluaciones.general');

    // Perfil y configuración
    Route::get('/docente/perfil', [DocenteController::class, 'perfil'])->name('docente.perfil');
    Route::get('/docente/configuracion', [DocenteController::class, 'configuracion'])->name('docente.configuracion');
    Route::post('/docente/configuracion', [DocenteController::class, 'actualizar'])->name('docente.configuracion.actualizar');
    Route::post('/docente/configuracion/actualizar', [DocenteController::class, 'actualizar'])->name('docente.actualizar');

    // Cursos y resultados
    Route::get('/docente/cursos', [DocenteController::class, 'vercurso'])->name('docente.cursos');
    Route::get('/docente/curso/{id}', [DocenteController::class, 'verDetalleCurso'])->name('docente.curso.ver');
    Route::post('docente/{id_curso}/subir', [DocenteController::class, 'subirMaterialApoyo'])->name('docente.materiales.subir');
});

// Middleware alumno
Route::middleware(['auth', 'alumno'])->group(function () {

    // Dashboard del alumno
    Route::get('/alumno', [AlumnoController::class, 'index'])->name('alumno.dashboard');

    // Cursos y horario
    Route::get('/alumno/horario', [AlumnoController::class, 'horario'])->name('alumno.horario');
    Route::get('/alumno/cursos', [AlumnoController::class, 'cursos'])->name('alumno.cursos');
    Route::get('/alumno/curso/{id}', [AlumnoController::class, 'verCurso'])->name('alumno.curso_detalle');
    Route::get('/alumno/curso/{id}/examen', function ($id) {
        return "Página de examen del curso $id";
    })->name('alumno.examen');

    // Pagos y resultados
    Route::get('/alumno/pagos', [AlumnoController::class, 'pagos'])->name('alumno.pagos');
    Route::get('/alumno/resultados', [AlumnoController::class, 'resultados'])->name('alumno.resultados');

    // Perfil del alumno
    Route::get('/alumno/perfil', [AlumnoController::class, 'perfil'])->name('alumno.perfil');
    Route::get('/alumno/perfil/editar', [AlumnoController::class, 'editar'])->name('alumno.editar');
    Route::post('/alumno/perfil/actualizar', [AlumnoController::class, 'actualizarPerfil'])->name('alumno.actualizar');
});

// Middleware tutor
Route::middleware(['auth', 'tutor'])->group(function () {

    // Dashboard del tutor
    Route::get('/tutor', [AlumnoController::class, 'index'])->name('tutor.dashboard');
    Route::get('/tutor/horario', [TutorController::class, 'Horario'])->name('tutor.horario');
    // Informes
    Route::get('/informes', [TutorController::class, 'informes'])->name('tutor.informes');
    Route::post('/informes/filtrar', [TutorController::class, 'filtrar'])->name('tutor.informes.filtrar');
    Route::get('/tutor/informes/alumno/{id}', [TutorController::class, 'detalleAlumno'])->name('tutor.informes.alumno');
    Route::get('/tutor/alumno/{id}/pdf', [TutorController::class, 'descargarPDF'])->name('tutor.descargarPDF');

    // Asistencia
    Route::get('/tutor/asistencia', [TutorController::class, 'index'])->name('asistencia.index');
    Route::post('/tutor/asistencia', [TutorController::class, 'store'])->name('tutor.asistencia.store');

    // Evaluaciones
    Route::get('/tutor/evaluaciones', [TutorController::class, 'evaluacion'])->name('tutor.evaluaciones');
    Route::post('/evaluaciones/guardar', [TutorController::class, 'guardarNota'])->name('tutor.evaluaciones.guardar');
    Route::post('/evaluaciones/nueva', [TutorController::class, 'crearEvaluacion'])->name('tutor.evaluaciones.nueva');
    
    // Perfil y configuración
    Route::get('/perfil', [TutorController::class, 'perfil'])->name('tutor.perfil');
    Route::get('/configuracion', [TutorController::class, 'perfiltutor'])->name('tutor.configuracion');
    Route::post('/actualizar', [TutorController::class, 'actualizar'])->name('tutor.actualizar');

});

// Dashboards alternativos


Route::get('/docente/dashboard', function () {
    return view('docente.dashboard');
})->name('docente.dashboard');

Route::get('/alumno/dashboard', function () {
    return view('alumno.dashboard');
})->name('alumno.dashboard');

