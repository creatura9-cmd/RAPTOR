@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#315A72]">
                Entregas
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Gestión y seguimiento de las entregas de motocicletas.
            </p>
        </div>

        <a
            href="{{ route('entrega.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-2 text-sm font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 5v14m-7-7h14"/>
            </svg>

            Registrar entrega
        </a>
    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de entregas --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-[#315A72]">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Entrega</th>
                        <th class="px-5 py-4 font-semibold">Motocicleta</th>
                        <th class="px-5 py-4 font-semibold">Propietario</th>
                        <th class="px-5 py-4 font-semibold">Fecha</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($entregas as $entrega)

                    @php
                        // Obtener la orden de trabajo y su recepción.
                        $ordenTrabajo = $entrega->ordenTrabajo;

                        $recepcion = $ordenTrabajo?->preorden?->diagnostico?->inventario?->recepcion
                            ?? $ordenTrabajo?->inventario?->recepcion;

                        // Folio único de la recepción.
                        $folioRecepcion = $recepcion
                            ? 'RECEPCIÓN # ' . str_pad((string) $recepcion, 3, '0', STR_PAD_LEFT)
                            : 'RECEPCIÓN';

                        // Obtener motocicleta y propietario.
                        $moto = $ordenTrabajo?->preorden?->diagnostico?->inventario?->moto
                            ?? $ordenTrabajo?->inventario?->moto;

                        $cliente = $moto?->cliente;
                    @endphp

                    <tbody x-data="{ modalAbierto: false }" class="divide-y divide-[#e8eff2]">

                        {{-- Fila de entrega --}}
                        <tr class="transition hover:bg-[#eaf2f6]">

                            {{-- Recepción --}}
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-700">
                                {{ $folioRecepcion }}
                            </td>

                            {{-- Motocicleta --}}
                            <td class="px-5 py-4 text-slate-600">
                                @if ($moto)
                                    <div class="font-semibold text-slate-700">
                                        {{ $moto->placa }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $moto->modelo ?? 'Modelo no disponible' }}
                                    </div>
                                @else
                                    <span class="text-slate-400">No disponible</span>
                                @endif
                            </td>

                            {{-- Propietario --}}
                            <td class="px-5 py-4 text-slate-600">
                                @if ($cliente)
                                    {{ $cliente->nombre }}
                                    {{ $cliente->apellido ?? '' }}
                                @else
                                    <span class="text-slate-400">No disponible</span>
                                @endif
                            </td>

                            {{-- Fecha --}}
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $entrega->fecha_entrega
                                    ? \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y')
                                    : 'Sin fecha' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles de la entrega"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-[#315A72] transition hover:bg-[#f0f5f7] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke-width="1.8"
                                             stroke="currentColor"
                                             class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.25 12s3.5-6.75 9.75-6.75S21.75 12 21.75 12 18.25 18.75 12 18.75 2.25 12 2.25 12Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('entrega.edit', $entrega->id_entrega) }}"
                                        title="Editar entrega"
                                        aria-label="Editar entrega"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke-width="1.8"
                                             stroke="currentColor"
                                             class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"/>
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form
                                        action="{{ route('entrega.destroy', $entrega->id_entrega) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de que desea eliminar esta entrega?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar entrega"
                                            aria-label="Eliminar entrega"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="1.8"
                                                 stroke="currentColor"
                                                 class="h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                        {{-- Modal de detalles --}}
                        <tr x-show="modalAbierto" x-cloak>
                            <td colspan="5" class="!p-0">

                                {{-- Fondo del modal --}}
                                <div
                                    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/50 p-4"
                                    @click.self="modalAbierto = false"
                                    @keydown.escape.window="modalAbierto = false"
                                >

                                    {{-- Contenedor blanco del modal --}}
                                    <div class="my-auto w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-xl">

                                        {{-- Encabezado del modal --}}
                                        <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-4">

                                            <div>
                                                <h2 class="text-lg font-bold text-[#315A72]">
                                                    Detalles de la entrega
                                                </h2>

                                                <p class="mt-1 text-sm text-slate-500">
                                                    {{ $folioRecepcion }}
                                                </p>
                                            </div>

                                            <button
                                                type="button"
                                                @click="modalAbierto = false"
                                                aria-label="Cerrar modal"
                                                class="rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-700"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke-width="1.8"
                                                     stroke="currentColor"
                                                     class="h-5 w-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M6 18 18 6M6 6l12 12"/>
                                                </svg>
                                            </button>

                                        </div>

                                        {{-- Información de la entrega --}}
                                        <div class="max-h-[65vh] space-y-6 overflow-y-auto p-6">

                                            {{-- Información general --}}
                                            <section>
                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-[#315A72]">
                                                    Información general
                                                </h3>

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Número de recepción
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $folioRecepcion }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Fecha de entrega
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $entrega->fecha_entrega
                                                                ? \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y')
                                                                : 'Sin fecha registrada' }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </section>

                                            {{-- Información de la motocicleta --}}
                                            <section>
                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-[#315A72]">
                                                    Motocicleta
                                                </h3>

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Placa
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $moto?->placa ?? 'No disponible' }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Modelo
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $moto?->modelo ?? 'No disponible' }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Año
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $moto?->anio ?? 'No disponible' }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Estado del inventario
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $ordenTrabajo?->inventario?->estado_inventario ?? 'No disponible' }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </section>

                                            {{-- Información del propietario --}}
                                            <section>
                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-[#315A72]">
                                                    Propietario
                                                </h3>

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Nombre completo
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            @if ($cliente)
                                                                {{ $cliente->nombre }}
                                                                {{ $cliente->apellido ?? '' }}
                                                            @else
                                                                No disponible
                                                            @endif
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg border border-[#dce8ed] p-4">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Documento
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $cliente?->documento ?? 'No disponible' }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg border border-[#dce8ed] p-4 sm:col-span-2">
                                                        <p class="text-xs font-medium text-slate-500">
                                                            Teléfono
                                                        </p>

                                                        <p class="mt-1 font-semibold text-slate-700">
                                                            {{ $cliente?->telefono ?? 'No disponible' }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </section>

                                            {{-- Observaciones --}}
                                            <section>
                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-[#315A72]">
                                                    Observaciones
                                                </h3>

                                                <div class="rounded-lg border border-[#dce8ed] bg-[#f8fafb] p-4">
                                                    <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600">
                                                        {{ $entrega->observaciones ?: 'Sin observaciones registradas.' }}
                                                    </p>
                                                </div>
                                            </section>

                                        </div>

                                        {{-- Único pie del modal --}}
                                        <div class="flex items-center justify-between border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Eliminar entrega: lado izquierdo --}}
                                            <form
                                                action="{{ route('entrega.destroy', $entrega->id_entrega) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Está seguro de que desea eliminar esta entrega?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    title="Eliminar entrega"
                                                    aria-label="Eliminar entrega"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.8"
                                                         stroke="currentColor"
                                                         class="h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"/>
                                                    </svg>
                                                </button>
                                            </form>

                                            {{-- Editar y cerrar: lado derecho --}}
                                            <div class="flex items-center justify-end gap-2">

                                                <a
                                                    href="{{ route('entrega.edit', $entrega->id_entrega) }}"
                                                    title="Editar entrega"
                                                    aria-label="Editar entrega"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#315A72] text-white transition hover:bg-[#294c61] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.8"
                                                         stroke="currentColor"
                                                         class="h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"/>
                                                    </svg>
                                                </a>

                                                <button
                                                    type="button"
                                                    @click="modalAbierto = false"
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
                    {{-- Se conserva el estado vacío sin texto adicional --}}
                @endforelse

            </table>
        </div>
    </div>

</div>

@endsection