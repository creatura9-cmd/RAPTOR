@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Gestión de clientes')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Gestión de clientes
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra la información personal y de contacto de los clientes.
            </p>
        </div>

        <a
            href="{{ route('cliente.create') }}"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>

            Nuevo cliente
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>
    
    {{-- Enlace temporal para configurar la contraseña --}}
    @if (session('enlace_invitacion'))
         <div class="rounded-xl border border-[#dce8ed] bg-[#f0f5f7] p-4">
            <h3 class="font-semibold text-[#315A72]">
                Enlace temporal para el portal del cliente
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Comparte este enlace únicamente con el cliente correspondiente.
                Podrá utilizarlo para establecer su contraseña.
            </p>

            <div class="mt-3 break-all rounded-lg border border-[#dce8ed] bg-white p-3 text-sm text-slate-700">
                {{ session('enlace_invitacion') }}
            </div>

            <div class="mt-3">
                <a
                    href="{{ session('enlace_invitacion') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center rounded-lg bg-[#315A72] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#294c61]"
                >
                    Abrir enlace de invitación
                </a>
            </div>
        </div>
    @endif

    {{-- Tabla de clientes --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Cliente</th>
                        <th class="px-5 py-4 font-semibold">Documento</th>
                        <th class="px-5 py-4 font-semibold">Teléfono</th>
                        <th class="px-5 py-4 font-semibold">Correo electrónico</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($clientes as $cliente)

                    {{-- Cada cliente controla su propio modal --}}
                    <tbody
                        x-data="{ modalAbierto: false }"
                        class="divide-y divide-[#e5edf1]"
                    >

                        {{-- Fila del cliente --}}
                        <tr class="transition hover:bg-[#eaf2f6]">

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-14a4 4 0 11-8 0 4 4 0 018 0z"
                                            />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            CLIENTE #{{ $cliente->id }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-700">
                                    {{ $cliente->tipo_documento }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $cliente->numero_documento }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $cliente->telefono ?: 'No registrado' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $cliente->correo_electronico ?: 'No registrado' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles del cliente"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-[#315A72] hover:bg-[#eaf2f6] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="block h-5 w-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"
                                            />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('cliente.edit', $cliente->id) }}"
                                        title="Editar"
                                        aria-label="Editar cliente"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="block h-5 w-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"
                                            />
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form
                                        action="{{ route('cliente.destroy', $cliente->id) }}"
                                        method="POST"
                                        class="flex items-center justify-center"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar"
                                            aria-label="Eliminar cliente"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="block h-5 w-5 shrink-0"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"
                                                />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                        {{-- Modal del cliente --}}
                        <tr x-show="modalAbierto" x-cloak>
                            <td colspan="5" class="!p-0">

                                <div
                                    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/60 p-4"
                                    @click.self="modalAbierto = false"
                                    @keydown.escape.window="modalAbierto = false"
                                >

                                    <div
                                        x-show="modalAbierto"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                        @click.stop
                                    >

                                        {{-- Encabezado --}}
                                        <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                            <div class="flex min-w-0 items-center gap-3">

                                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="1.7"
                                                            d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-14a4 4 0 11-8 0 4 4 0 018 0z"
                                                        />
                                                    </svg>
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="break-words text-lg font-bold text-slate-800">
                                                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                                                    </h2>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Información del cliente #{{ $cliente->id }}
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Cerrar --}}
                                            <button
                                                type="button"
                                                @click="modalAbierto = false"
                                                aria-label="Cerrar modal"
                                                title="Cerrar"
                                                class="ml-3 flex-shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>

                                        </div>

                                        {{-- Información completa --}}
                                        <div class="max-h-[65vh] overflow-y-auto p-6">

                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Datos personales y de contacto
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                {{-- Nombre --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Nombre completo
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                                                    </p>
                                                </div>

                                                {{-- Identificador --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Identificador del cliente
                                                    </p>

                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        #{{ $cliente->id }}
                                                    </p>
                                                </div>

                                                {{-- Tipo de documento --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Tipo de documento
                                                    </p>

                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        {{ $cliente->tipo_documento ?: 'No registrado' }}
                                                    </p>
                                                </div>

                                                {{-- Número de documento --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Número de documento
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $cliente->numero_documento ?: 'No registrado' }}
                                                    </p>
                                                </div>

                                                {{-- Teléfono --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Teléfono
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $cliente->telefono ?: 'No registrado' }}
                                                    </p>
                                                </div>

                                                {{-- Correo --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Correo electrónico
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $cliente->correo_electronico ?: 'No registrado' }}
                                                    </p>
                                                </div>

                                                {{-- Dirección --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Dirección
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $cliente->direccion ?: 'No registrada' }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Pie del modal --}}
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Eliminar --}}
                                            <form
                                                action="{{ route('cliente.destroy', $cliente->id) }}"
                                                method="POST"
                                                class="flex items-center justify-center"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    title="Eliminar cliente"
                                                    aria-label="Eliminar cliente"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="block h-5 w-5 shrink-0"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"
                                                        />
                                                    </svg>
                                                </button>
                                            </form>
                                            
                                            {{-- Estado del acceso al portal --}}
                                            @if (!$cliente->usuarioPortal)

                                                    {{-- Estado 1: el cliente no tiene cuenta --}}
                                                    <form
                                                        action="{{ route('cliente.habilitar_portal', $cliente->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('¿Deseas habilitar el acceso al portal para este cliente?')"
                                                    >
                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center gap-2 rounded-lg border border-[#315A72] bg-white px-3 py-2 text-sm font-semibold text-[#315A72] transition hover:bg-[#f0f5f7] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 1 1 5.25 2.1M12 15l6-6m-6 6-2.25 2.25a2.12 2.12 0 0 1-3 0l-.75-.75a2.12 2.12 0 0 1 0-3L8.25 12" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25a3 3 0 1 1 5.25-2.1" />
                                                            </svg>

                                                                                Habilitar acceso al portal
                                                                            </button>
                                                                        </form>

                                                                    @elseif (is_null($cliente->usuarioPortal->password_configurada_at))

                                                                        {{-- Estado 2: cuenta creada, contraseña pendiente --}}
                                                                        <form
                                                                            action="{{ route('cliente.reenviar_invitacion', $cliente->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('¿Deseas generar una nueva invitación para este cliente?')"
                                                                        >
                                                                            @csrf

                                                                            <button
                                                                                type="submit"
                                                                                class="inline-flex items-center gap-2 rounded-lg border border-[#315A72] bg-white px-3 py-2 text-sm font-semibold text-[#315A72] transition hover:bg-[#f0f5f7] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                                                            >
                                                                                <svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24"
                                                                                    stroke-width="1.8"
                                                                                    stroke="currentColor"
                                                                                    class="h-5 w-5"
                                                                                >
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        d="M3 8l9 6 9-6M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"
                                                                                    />
                                                                                </svg>

                                                                                Reenviar invitación
                                                                            </button>
                                                                        </form>

                                                                    @else

                                                                        {{-- Estado 3: contraseña configurada --}}
                                                                        <span
                                                                            class="inline-flex items-center gap-2 rounded-lg border border-[#dce8ed] bg-[#f0f5f7] px-3 py-2 text-sm font-semibold text-[#315A72]"
                                                                        >
                                                                            <svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke-width="1.8"
                                                                                stroke="currentColor"
                                                                                class="h-5 w-5"
                                                                            >
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="m5 12 4 4L19 6"
                                                                                />
                                                                            </svg>

                                                                            Acceso habilitado
                                                                        </span>

                                                                    @endif

                                                                    {{-- Acciones del modal --}}
                                                                    <div class="flex flex-wrap items-center gap-2">

                                                                        {{-- Editar --}}
                                                                        <a
                                                                            href="{{ route('cliente.edit', $cliente->id) }}"
                                                                            title="Editar cliente"
                                                                            aria-label="Editar cliente"
                                                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#315A72] text-white transition hover:bg-[#294c61]"
                                                                        >
                                                                            <svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke-width="1.8"
                                                                                stroke="currentColor"
                                                                                class="block h-5 w-5"
                                                                            >
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"
                                                                                />
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"
                                                                                />
                                                                            </svg>
                                                                        </a>

                                                                        {{-- Cerrar --}}
                                                                        <button
                                                                            type="button"
                                                                            @click="modalAbierto = false"
                                                                            title="Cerrar"
                                                                            aria-label="Cerrar modal"
                                                                            class="rounded-lg border border-[#dce8ed] bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                                                                        >
                                                                            Cerrar
                                                                        </button>

                                                                    </div>

                                                                </div>

                                    </div>
                                </div>

                            </td>
                        </tr>

                    </tbody>

                @empty

                    {{-- Estado vacío intencionalmente sin contenido --}}

                @endforelse

            </table>

        </div>
    </div>

</div>

@endsection