<?php

namespace App\Repository;

use App\Models\Cliente;
use App\Models\User;

class ClienteRepository
{
    public function listartodo()
    {
        return Cliente::with([
            'motos.marca',
            'usuarioPortal',
        ])->get();
    }

    public function store(array $datos)
    {
        Cliente::create($datos);
    }

    public function edit(int $id)
    {
        return Cliente::findOrFail($id);
    }

    public function existeUsuarioParaCliente(int $clienteId): bool
    {
        return User::where('id_cliente', $clienteId)->exists();
    }

    public function existeCorreoEnUso(string $correo): bool
    {
        return User::where('email', $correo)->exists();
    }

    public function existeCorreoEnUsoPorOtroUsuario(
        string $correo,
        int $usuarioId
    ): bool {
        return User::where('email', $correo)
            ->where('id', '!=', $usuarioId)
            ->exists();
    }

    public function obtenerUsuarioPorCliente(int $clienteId): ?User
    {
        return User::where('id_cliente', $clienteId)->first();
    }

    public function crearUsuarioPortal(
        string $nombre,
        string $correo,
        string $password,
        int $clienteId
    ): User {
        $usuario = new User();

        $usuario->name = $nombre;
        $usuario->email = $correo;
        $usuario->password = $password;
        $usuario->rol = 'cliente';
        $usuario->id_cliente = $clienteId;

        $usuario->save();

        return $usuario;
    }

    public function update(int $id, array $datos)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->update($datos);

        return $cliente;
    }

    public function actualizarCorreoUsuarioPortal(
        int $usuarioId,
        string $correo
    ): void {
        $usuario = User::findOrFail($usuarioId);

        $usuario->email = $correo;
        $usuario->save();
    }

    public function destroy(int $id)
    {
        Cliente::destroy($id);
    }
}