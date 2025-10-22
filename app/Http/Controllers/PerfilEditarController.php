<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function subirImagen(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Guardar imagen en storage/app/public/perfiles
        $path = $request->file('imagen')->store('perfiles', 'public');

        // Guardar ruta en base de datos (asumiendo que tu tabla users tiene campo 'foto')
        $user->foto = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'imagen' => asset('storage/' . $path)
        ]);
    }
}
