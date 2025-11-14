<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller{
    // Mostrar formulario de login
    public function showLogin(){
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $credentials['email'])->first();

        if ($usuario && Hash::check($credentials['password'], $usuario->password)) {
            if ($usuario->estado == 1) {
                Auth::login($usuario);
                $request->session()->regenerate();

                // Redirección según tipo
                switch ($usuario->id_tipo) {
                    case 1: return redirect()->route('admin.dashboard');
                    case 2: return redirect()->route('docente.dashboard');
                    case 3: return redirect()->route('alumno.dashboard');
                    case 4: return redirect()->route('tutor.dashboard');
                    default: return redirect('/');
                }
            } else {
                return back()->withErrors(['email' => 'Tu cuenta está inactiva.']);
            }
        }

        return back()->withErrors(['email' => 'Las credenciales no son válidas.'])->onlyInput('email');
    }

    // Cerrar sesión
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
