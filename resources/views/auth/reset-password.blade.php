
@extends('layouts.auth')

@section('content')
<div class="mx-auto w-full max-w-md rounded-2xl border border-[#dce8ed] bg-white p-6 shadow-sm">

    {{-- Logo oficial de RAPTOR --}}
    <div class="mb-6 flex justify-center">
        <img
            src="{{ asset('images/logoRAPTOR.png') }}"
            alt="Logo oficial de RAPTOR"
            class="h-auto max-h-28 w-auto max-w-full object-contain"
        >
    </div>
    
    <h1 class="mb-2 text-center text-2xl font-bold text-[#315A72]">
         RAPTOR
    </h1>

    <p class="mb-6 text-center text-sm text-gray-600">
        Configurar una contraseña para ingresar al portal de clientes.
    </p>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                Correo electrónico
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                required
                readonly
                class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2"
            >
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-gray-700">
                Nueva contraseña
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
                class="w-full rounded-lg border border-gray-300 px-3 py-2"
            >
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">
                Confirmar contraseña
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
                class="w-full rounded-lg border border-gray-300 px-3 py-2"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-[#315A72] px-4 py-3 font-semibold text-white transition hover:bg-[#26485c]"
        >
            Guardar contraseña
        </button>
    </form>
</div>
@endsection
