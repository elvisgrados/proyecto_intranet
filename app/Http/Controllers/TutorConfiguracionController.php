<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TutorConfiguracionController extends Controller{

    public function perfil(){
    $usuario = Auth::user();

    $docente = \App\Models\Docente::where('id_usuario', $usuario->id_usuario)->first();


    return view('tutor.perfil', compact('usuario', 'docente'));
    }
    public function index(){
            $usuario = Auth::user();

            // Obtener datos del docente asociado al usuario
            $docente = \App\Models\Docente::where('id_usuario', $usuario->id_usuario)->first();

            return view('tutor.configuracion', compact('usuario', 'docente'));
        }

    public function actualizar(Request $request){
        // Obtener el usuario autenticado (tutor)
        $usuario = Auth::user();

        // Validar datos
        $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'required|email|max:100|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'telefono'  => 'nullable|string|max:20',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        // Actualizar campos básicos
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        // Manejo de foto de perfil
        if ($request->hasFile('foto')) {
            // Eliminar la anterior si existe
            if ($usuario->foto && file_exists(public_path($usuario->foto))) {
                @unlink(public_path($usuario->foto));
            }

            $nombreArchivo = 'tutor_' . $usuario->id_usuario . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/fotos'), $nombreArchivo);
            $usuario->foto = 'uploads/fotos/' . $nombreArchivo;
        }

        $usuario->save();

        // Refrescar la sesión del tutor para evitar cierre de sesión
        Auth::login($usuario);

        return redirect()->back()->with('success', 'Configuración de tutor actualizada correctamente.');
    }





    public function verCurso($id){
        $curso = \App\Models\Curso::with([
            'horarios',
            'alumnos.usuario',
            'temas', // relación con los temas (por semanas)
            'autoevaluaciones' // relación con evaluaciones semanales
        ])->findOrFail($id);

        return view('docente.curso-detalle', compact('curso'));
    }
}
