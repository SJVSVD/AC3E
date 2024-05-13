<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials, $request->filled('rememberMe'))) {
            $request->session()->regenerate();
    
            return redirect()->intended('dashboard');
        }
    
        return back()->withErrors([
            'email' => 'The data entered is incorrect.',
        ])->withInput($request->only('email', 'rememberMe'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
