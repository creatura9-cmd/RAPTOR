<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    /**
     * Mostrar formulario para solicitar recuperación de contraseña.
     */
    public function mostrarSolicitud()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar enlace de recuperación de contraseña.
     */
    public function enviarEnlace(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $estado = Password::sendResetLink(
            $request->only('email')
        );

        if ($estado === Password::RESET_LINK_SENT) {
            return back()->with('status', __($estado));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => __($estado),
            ]);
    }


    /**
     * Mostrar formulario para configurar o restablecer contraseña.
     */
    public function mostrarFormulario(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Procesar el restablecimiento de contraseña.
     */
    public function restablecer(Request $request)
    {
        $datos = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $estado = Password::reset(
            $datos,
            function (User $usuario, string $password) {
                $usuario->password = $password;
                $usuario->password_configurada_at = now();
                $usuario->setRememberToken(Str::random(60));
                $usuario->save();
            }
        );

        if ($estado === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Contraseña configurada correctamente. Ya puedes iniciar sesión.'
                );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => __($estado),
            ]);
    }
}
