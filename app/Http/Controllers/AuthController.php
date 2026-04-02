<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            
            if (!$user->active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu cuenta está desactivada.']);
            }

            return match($user->role) {
                'admin', 'secretaria' => redirect()->route('dashboard'),
                'padre'               => redirect()->route('padre.dashboard'),
                'docente'             => redirect()->route('docente.dashboard'),
                default               => redirect()->route('login'),
            };
        }

        return back()->withErrors(['email' => 'Correo o contraseña incorrectos.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
