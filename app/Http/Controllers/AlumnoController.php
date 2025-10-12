<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index(){
        return view('alumno.dashboard');
    }

    public function horario()
    {
        return view('alumno.horario');
    }

    public function cursos()
    {
        return view('alumno.cursos');
    }

    public function pagos()
    {
        return view('docente.pagos');
    }

    public function resultados()
    {
        return view('docente.resultados');
    }

}
