<?php

// app/Http/Controllers/ConfiguracionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Validar campos
        $request->validate([
            'nombres' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048', // Máx 2MB
        ]);

        // Actualizar información
        $usuario->nombres = $request->nombres;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
dd($request->file('foto'));

        // 📸 Subida de foto de perfil
        if ($request->hasFile('foto')) {
            // Borrar la anterior si existía
            if ($usuario->foto && file_exists(public_path($usuario->foto))) {
                unlink(public_path($usuario->foto));
            }

            $nombreArchivo = 'usuario_' . $usuario->id_usuario . '.' . $request->foto->extension();
            $ruta = $request->foto->move(public_path('uploads/fotos'), $nombreArchivo);
            $usuario->foto = 'uploads/fotos/' . $nombreArchivo;
        }

        $usuario->save();

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
