<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\DocenteCursoController;
use App\Http\Controllers\EvaluacionesDocenteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\HorarioController;

Route::middleware(['auth', 'docente'])->group(function () {
    Route::get('/docente', [DocenteController::class, 'index'])->name('docente.dashboard');
    Route::get('/docente/horario', [HorarioController::class, 'index'])->name('docente.horario');
    Route::get('/docente/cursos', [DocenteCursoController::class, 'index'])->name('docente.cursos');
    Route::get('/docente/datos', [DocenteController::class, 'datos'])->name('docente.datos');
    Route::get('/docente/comunicaciones', [DocenteController::class, 'comunicaciones'])->name('docente.comunicaciones');
    Route::get('/docente/informes', [DocenteController::class, 'informes'])->name('docente.informes');
    Route::get('/docente/asistencia', [AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::post('/docente/asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store');
    Route::get('/docente/evaluaciones', [EvaluacionesDocenteController::class, 'index'])->name('docente.evaluaciones');
    Route::get('/docente/configuracion', [ConfiguracionController::class, 'index'])->name('docente.configuracion');
    Route::post('/docente/configuracion/actualizar', [ConfiguracionController::class, 'actualizar'])->name('docente.actualizar');
    Route::get('/docente/curso/{id}', [DocenteCursoController::class, 'verCurso'])->name('docente.curso.ver');
Route::get('/docente/evaluacion/{id}/resultados', [DocenteCursoController::class, 'verResultados'])->name('docente.evaluacion.resultados');
    
});

use App\Http\Controllers\AlumnoController;

Route::middleware(['auth'])->group(function () {

    // 📌 Dashboard del alumno
    Route::get('/alumno', [AlumnoController::class, 'index'])->name('alumno.dashboard');

    // 📚 Cursos y horario
    Route::get('/alumno/horario', [AlumnoController::class, 'horario'])->name('alumno.horario');
    Route::get('/alumno/cursos', [AlumnoController::class, 'cursos'])->name('alumno.cursos');
    Route::get('/alumno/curso/{id}', [AlumnoController::class, 'verCurso'])->name('alumno.curso_detalle');
    Route::get('/alumno/curso/{id}/examen', function ($id) {
        return "Página de examen del curso $id";
    })->name('alumno.examen');

    // 💳 Pagos y resultados
    Route::get('/alumno/pagos', [AlumnoController::class, 'pagos'])->name('alumno.pagos');
    Route::get('/alumno/resultados', [AlumnoController::class, 'resultados'])->name('alumno.resultados');

    // 👤 PERFIL DEL ALUMNO
    Route::get('/alumno/perfil', [AlumnoController::class, 'perfil'])->name('alumno.perfil');
    Route::get('/alumno/perfil/editar', [AlumnoController::class, 'editar'])->name('alumno.editar');
    Route::post('/alumno/perfil/actualizar', [AlumnoController::class, 'actualizarPerfil'])->name('alumno.actualizar');
   
});

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboards
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/docente/dashboard', function () {
    return view('docente.dashboard');
})->name('docente.dashboard');

Route::get('/alumno/dashboard', function () {
    return view('alumno.dashboard');
})->name('alumno.dashboard');
