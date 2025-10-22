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

        // 📸 Manejo de foto de perfil
        if ($request->hasFile('foto')) {
    dd('📸 El archivo llegó correctamente', $request->foto->getClientOriginalExtension());
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
