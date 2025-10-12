<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index(){
        return view('docente.dashboard');
    }

    public function horario()
    {
        return view('docente.horario');
    }

    public function cursos()
    {
        return view('docente.cursos');
    }

    public function datos()
    {
        return view('docente.datos');
    }

    public function comunicaciones()
    {
        return view('docente.comunicaciones');
    }

    public function informes()
    {
        return view('docente.informes');
    }
}
