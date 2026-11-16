<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credenciales, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales ingresadas no son correctas.',
            ]);
        }

        $request->session()->regenerate();

        $usuario = Auth::user();

        if ($usuario->rol === 'administrador') {
            return redirect()->route('dashboard.index');
        }

        if ($usuario->rol === 'cliente') {
            if (!$usuario->id_cliente || !$usuario->cliente) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'La cuenta no tiene un cliente asociado.',
                    ]);
            }

            return redirect()->route('cliente.portal');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors([
                'email' => 'El rol de esta cuenta todavía no tiene acceso habilitado.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
