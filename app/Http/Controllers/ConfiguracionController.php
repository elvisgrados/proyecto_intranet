<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        return view('docente.configuracion', compact('usuario'));
    }

    public function actualizar(Request $request)
    {
        $usuario = Auth::user();

        // Validación de campos
        $request->validate([
            'nombres' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        // Actualizar datos básicos
        $usuario->nombres = $request->nombres;
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
