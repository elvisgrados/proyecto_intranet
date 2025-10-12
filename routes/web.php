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
    return view('docente.dashboard');
});

use App\Http\Controllers\DocenteController;

Route::middleware(['auth', 'docente'])->group(function () {
    Route::get('/docente', [DocenteController::class, 'index'])->name('docente.dashboard');
    Route::get('/docente/horario', [DocenteController::class, 'horario'])->name('docente.horario');
    Route::get('/docente/cursos', [DocenteController::class, 'cursos'])->name('docente.cursos');
    Route::get('/docente/datos', [DocenteController::class, 'datos'])->name('docente.datos');
    Route::get('/docente/comunicaciones', [DocenteController::class, 'comunicaciones'])->name('docente.comunicaciones');
    Route::get('/docente/informes', [DocenteController::class, 'informes'])->name('docente.informes');
});

use App\Http\Controllers\AlumnoController;

Route::middleware(['auth', 'alumno'])->group(function () {
    Route::get('/alumno', [DocenteController::class, 'index'])->name('alumno.dashboard');
    Route::get('/alumno/horario', [DocenteController::class, 'horario'])->name('alumno.horario');
    Route::get('/alumno/cursos', [DocenteController::class, 'cursos'])->name('alumno.cursos');
    Route::get('/alumno/datos', [DocenteController::class, 'pagos'])->name('alumno.pagos');
    Route::get('/alumno/comunicaciones', [DocenteController::class, 'resultados'])->name('alumno.resultados');
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
