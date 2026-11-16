<?php

namespace App\Service;

use App\Repository\ClienteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ClienteService
{
    private ClienteRepository $cliente_repository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->cliente_repository = $clienteRepository;
    }

    public function listartodo()
    {
        return $this->cliente_repository->listartodo();
    }

    public function store(array $datos)
    {
        $this->cliente_repository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->cliente_repository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        return DB::transaction(function () use ($id, $datos) {

            $usuario = $this->cliente_repository
                ->obtenerUsuarioPorCliente($id);

            if (array_key_exists('correo_electronico', $datos)) {

                $correo = trim((string) ($datos['correo_electronico'] ?? ''));

                if ($usuario && $correo === '') {
                    throw ValidationException::withMessages([
                        'correo_electronico' => 'No puedes dejar vacío el correo porque este cliente tiene una cuenta del portal.',
                    ]);
                }

                if (
                    $usuario
                    && $this->cliente_repository
                        ->existeCorreoEnUsoPorOtroUsuario($correo, $usuario->id)
                ) {
                    throw ValidationException::withMessages([
                        'correo_electronico' => 'Este correo electrónico ya está asociado a otra cuenta.',
                    ]);
                }

                $datos['correo_electronico'] = $correo !== ''
                    ? $correo
                    : null;
            }

            $this->cliente_repository->update($id, $datos);

            if (
                $usuario
                && array_key_exists('correo_electronico', $datos)
            ) {
                $this->cliente_repository->actualizarCorreoUsuarioPortal(
                    $usuario->id,
                    $datos['correo_electronico']
                );
            }
        });
    }

    public function habilitarPortal(int $id): array
    {
        $cliente = $this->cliente_repository->edit($id);

        if (empty($cliente->correo_electronico)) {
            return [
                'error' => 'El cliente debe tener un correo electrónico para habilitar su acceso.',
            ];
        }

        $correo = trim($cliente->correo_electronico);

        if ($this->cliente_repository->existeUsuarioParaCliente($cliente->id)) {
            return [
                'error' => 'Este cliente ya tiene una cuenta de acceso al portal.',
            ];
        }

        if ($this->cliente_repository->existeCorreoEnUso($correo)) {
            return [
                'error' => 'Este correo electrónico ya está asociado a otra cuenta.',
            ];
        }

        $usuario = $this->cliente_repository->crearUsuarioPortal(
            trim($cliente->nombre . ' ' . $cliente->apellido),
            $correo,
            Str::random(40),
            $cliente->id
        );

        $token = Password::createToken($usuario);

        $enlace = route('password.reset', [
            'token' => $token,
            'email' => $usuario->email,
        ]);

        Mail::raw(
            "Hola {$usuario->name}.\n\n"
            . "Tu acceso al portal de RAPTOR ha sido habilitado.\n\n"
            . "Para activar tu cuenta y configurar tu contraseña, ingresa al siguiente enlace:\n\n"
            . $enlace
            . "\n\nEste enlace es temporal y de uso personal.\n\n"
            . "Saludos,\nEquipo RAPTOR",
            function ($message) use ($correo) {
                $message->to($correo)
                    ->subject('Invitación para activar tu cuenta - RAPTOR');
            }
        );

        return [
            'success' => 'Acceso al portal habilitado. Invitación enviada al correo del cliente.',
            'enlace' => $enlace,
        ];
    }

    public function reenviarInvitacion(int $id): array
    {
        $cliente = $this->cliente_repository->edit($id);

        $usuario = $this->cliente_repository
            ->obtenerUsuarioPorCliente($cliente->id);

        if (!$usuario) {
            return [
                'error' => 'Este cliente todavía no tiene una cuenta del portal.',
            ];
        }

        if ($usuario->password_configurada_at !== null) {
            return [
                'error' => 'El cliente ya configuró su contraseña. No necesita la invitación inicial.',
            ];
        }

        $token = Password::createToken($usuario);

        $enlace = route('password.reset', [
            'token' => $token,
            'email' => $usuario->email,
        ]);

        Mail::raw(
            "Hola {$usuario->name}.\n\n"
            . "Te enviamos nuevamente la invitación para activar tu cuenta en RAPTOR.\n\n"
            . "Configura tu contraseña ingresando al siguiente enlace:\n\n"
            . $enlace
            . "\n\nEste enlace es temporal y de uso personal.\n\n"
            . "Saludos,\nEquipo RAPTOR",
            function ($message) use ($usuario) {
                $message->to($usuario->email)
                    ->subject('Nueva invitación para activar tu cuenta - RAPTOR');
            }
        );

        return [
            'success' => 'Invitación enviada correctamente al correo del cliente.',
            'enlace' => $enlace,
        ];
    }

    public function destroy(int $id)
    {
        $this->cliente_repository->destroy($id);
    }
}