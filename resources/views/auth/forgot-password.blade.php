@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('content')

<div class="mx-auto w-full max-w-md rounded-2xl border border-[#dce8ed] bg-white p-8 shadow-sm">

    {{-- Logo oficial de RAPTOR --}}
    <div class="mb-3 flex justify-center">
        <img
            src="{{ asset('images/logoRAPTOR.png') }}"
            alt="Logo oficial de RAPTOR"
            class="h-24 w-auto max-w-full object-contain"
        >
    </div>

    {{-- Nombre del sistema --}}
    <h1 class="mb-2 text-center text-2xl font-bold text-[#315A72]">
        RAPTOR
    </h1>

    <p class="mb-2 text-center font-semibold text-slate-700">
        Recuperar contraseña
    </p>

    <p class="mb-6 text-center text-sm text-slate-500">
        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    {{-- Mensaje de éxito --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    {{-- Mensajes de validación --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Formulario de recuperación --}}
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">
                Correo electrónico
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="correo@ejemplo.com"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-[#315A72] px-4 py-3 font-semibold text-white transition hover:bg-[#26485c]"
        >
            Enviar enlace de recuperación
        </button>
    </form>

    {{-- Volver al inicio de sesión --}}
    <div class="mt-5 text-center">
        <a
            href="{{ route('login') }}"
            class="text-sm font-medium text-[#315A72] transition hover:text-[#26485c] hover:underline"
        >
            Volver al inicio de sesión
        </a>
    </div>

</div>

@endsection
