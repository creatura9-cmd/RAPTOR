@extends('layouts.app')

@section('title', 'Preórdenes')
@section('page-title', 'Gestión de preórdenes')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Gestión de preórdenes
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra los presupuestos de reparación y sus repuestos.
            </p>
        </div>

        <a
            href="{{ route('preorden.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#315A72] px-3 py-2 text-sm font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Nueva preorden
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de preórdenes --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Preorden</th>
                        <th class="px-5 py-4 font-semibold">Motocicleta</th>
                        <th class="px-5 py-4 font-semibold">Placa</th>
                        <th class="px-5 py-4 font-semibold">Total</th>
                        <th class="px-5 py-4 font-semibold">Estado</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($preordenes as $preorden)

                    {{-- Cada preorden controla su propio modal --}}
                    <tbody
                        x-data="{ modalAbierto: false }"
                        class="divide-y divide-[#e5edf1]"
                    >

                        {{-- Fila de la preorden --}}
                        <tr class="transition hover:bg-[#eaf1f4]">

                            {{-- Identificador --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                                        <span class="block h-4 w-4">
                                            @include('layouts.partials.icons', ['icon' => 'alarm-clock'])
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            RECEPCIÓN # {{ str_pad((string) ($preorden->diagnostico?->inventario?->recepcion ?? ''), 3, '0', STR_PAD_LEFT) }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Presupuesto de reparación
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Motocicleta --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700">
                                    {{ $preorden->diagnostico?->inventario?->moto?->marca?->nombre_marca ?? 'Marca no disponible' }}
                                    {{ $preorden->diagnostico?->inventario?->moto?->modelo ?? '' }}
                                </p>
                            </td>

                            {{-- Placa --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $preorden->diagnostico?->inventario?->moto?->placa ?? 'No registrada' }}
                            </td>

                            {{-- Total --}}
                            <td class="px-5 py-4">
                                <span class="font-bold text-[#315A72]">
                                    ${{ number_format($preorden->total, 2, ',', '.') }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4">

                                @if ($preorden->estado === 'pendiente')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                        Pendiente
                                    </span>

                                @elseif ($preorden->estado === 'aprobada')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                        Aprobada
                                    </span>

                                @elseif ($preorden->estado === 'rechazada')
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                        Rechazada
                                    </span>

                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $preorden->estado ?: 'Sin estado' }}
                                    </span>
                                @endif

                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles de la preorden"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-[#315A72] hover:bg-[#eaf2f6] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="block h-[18px] w-[18px]"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"
                                            />
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('preorden.edit', $preorden->id) }}"
                                        title="Editar"
                                        aria-label="Editar preorden"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="block h-[18px] w-[18px]"
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
                                        action="{{ route('preorden.destroy', $preorden->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta preorden?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar"
                                            aria-label="Eliminar preorden"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="block h-[18px] w-[18px] shrink-0"
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

                        {{-- Modal de la preorden --}}
                        <tr x-show="modalAbierto" x-cloak>
                            <td colspan="6" class="!p-0">

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
                                        class="my-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                        @click.stop
                                    >

                                        {{-- Encabezado del modal --}}
                                        <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                            <div class="flex min-w-0 items-center gap-3">

                                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                                                    <span class="block h-4 w-4">
                                                        @include('layouts.partials.icons', ['icon' => 'alarm-clock'])
                                                    </span>
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="break-words text-lg font-bold text-slate-800">
                                                        RECEPCIÓN # {{ str_pad((string) ($preorden->diagnostico?->inventario?->recepcion ?? ''), 3, '0', STR_PAD_LEFT) }}
                                                    </h2>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Información del presupuesto
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Cerrar --}}
                                            <button
                                                type="button"
                                                @click="modalAbierto = false"
                                                title="Cerrar"
                                                aria-label="Cerrar modal"
                                                class="ml-3 flex-shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>

                                        </div>

                                        {{-- Información completa --}}
                                        <div class="max-h-[65vh] overflow-y-auto p-6">

                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Datos del presupuesto y del propietario
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                {{-- Identificador --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Folio de recepción
                                                    </p>
                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        RECEPCIÓN # {{ str_pad((string) ($preorden->diagnostico?->inventario?->recepcion ?? ''), 3, '0', STR_PAD_LEFT) }}
                                                    </p>
                                                </div>

                                                {{-- Diagnóstico --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Tipo de registro
                                                    </p>
                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        Diagnóstico relacionado
                                                    </p>
                                                </div>

                                                {{-- Motocicleta --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Motocicleta
                                                    </p>
                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $preorden->diagnostico?->inventario?->moto?->marca?->nombre_marca ?? 'Marca no disponible' }}
                                                        {{ $preorden->diagnostico?->inventario?->moto?->modelo ?? '' }}
                                                    </p>
                                                    <p class="mt-1 text-sm text-slate-500">
                                                        Placa:
                                                        {{ $preorden->diagnostico?->inventario?->moto?->placa ?? 'No registrada' }}
                                                    </p>
                                                </div>

                                                {{-- Propietario --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Propietario
                                                    </p>
                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $preorden->diagnostico?->inventario?->moto?->cliente?->nombre ?? '' }}
                                                        {{ $preorden->diagnostico?->inventario?->moto?->cliente?->apellido ?? '' }}
                                                    </p>
                                                </div>

                                                {{-- Documento --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Documento del propietario
                                                    </p>
                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $preorden->diagnostico?->inventario?->moto?->cliente?->numero_documento ?? 'No disponible' }}
                                                    </p>
                                                </div>

                                                {{-- Descripción --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Descripción
                                                    </p>
                                                    <p class="mt-2 whitespace-pre-line break-words text-slate-700">
                                                        {{ $preorden->descripcion ?: 'Sin descripción' }}
                                                    </p>
                                                </div>

                                                {{-- Mano de obra --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Mano de obra
                                                    </p>
                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        ${{ number_format($preorden->mano_obra, 2, ',', '.') }}
                                                    </p>
                                                </div>

                                                {{-- Estado --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Estado
                                                    </p>
                                                    <div class="mt-2">
                                                        @if ($preorden->estado === 'pendiente')
                                                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                                                Pendiente
                                                            </span>
                                                        @elseif ($preorden->estado === 'aprobada')
                                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                                Aprobada
                                                            </span>
                                                        @elseif ($preorden->estado === 'rechazada')
                                                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                                                Rechazada
                                                            </span>
                                                        @else
                                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                                {{ $preorden->estado ?: 'Sin estado' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Repuestos --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">

                                                    <div class="flex items-center justify-between gap-3">
                                                        <p class="text-xs font-medium text-slate-400">
                                                            Repuestos del presupuesto
                                                        </p>

                                                        <span class="rounded-full bg-[#f0f5f7] px-2.5 py-1 text-xs font-semibold text-[#315A72]">
                                                            {{ $preorden->repuestos->count() }}
                                                        </span>
                                                    </div>

                                                    <div class="mt-3 space-y-3">

                                                        @forelse ($preorden->repuestos as $preordenRepuesto)

                                                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">

                                                                <p class="break-words text-sm font-semibold text-slate-700">
                                                                    {{ $preordenRepuesto->repuesto->nombre ?? 'Repuesto no disponible' }}
                                                                </p>

                                                                <div class="mt-2 grid grid-cols-1 gap-2 text-xs text-slate-500 sm:grid-cols-3">

                                                                    <p>
                                                                        Cantidad:
                                                                        <span class="font-semibold text-slate-700">
                                                                            {{ $preordenRepuesto->cantidad }}
                                                                        </span>
                                                                    </p>

                                                                    <p>
                                                                        Precio unitario:
                                                                        <span class="font-semibold text-slate-700">
                                                                            ${{ number_format($preordenRepuesto->precio_unitario, 2, ',', '.') }}
                                                                        </span>
                                                                    </p>

                                                                    <p>
                                                                        Subtotal:
                                                                        <span class="font-semibold text-slate-700">
                                                                            ${{ number_format($preordenRepuesto->subtotal, 2, ',', '.') }}
                                                                        </span>
                                                                    </p>

                                                                </div>

                                                            </div>

                                                        @empty

                                                            <p class="rounded-lg border border-dashed border-[#bfd2dc] p-3 text-sm text-slate-500">
                                                                Todavía no se han agregado repuestos a esta preorden.
                                                            </p>

                                                        @endforelse

                                                    </div>

                                                    <div class="mt-4 border-t border-slate-100 pt-3">
                                                        <p class="text-xs text-slate-500">
                                                            Total de repuestos
                                                        </p>
                                                        <p class="mt-1 font-semibold text-slate-800">
                                                            ${{ number_format($preorden->repuestos->sum('subtotal'), 2, ',', '.') }}
                                                        </p>
                                                    </div>

                                                </div>

                                                {{-- Total de la preorden --}}
                                                <div class="rounded-xl bg-[#f0f5f7] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-500">
                                                        Total de la preorden
                                                    </p>
                                                    <p class="mt-1 text-xl font-bold text-[#315A72]">
                                                        ${{ number_format($preorden->total, 2, ',', '.') }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Pie del modal --}}
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Agregar repuestos --}}
                                            <a
                                                href="{{ route('preorden_repuesto.create', ['id_preorden' => $preorden->id]) }}"
                                                title="Agregar repuestos"
                                                aria-label="Agregar repuestos a la preorden"
                                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#bfd2dc] bg-white px-4 py-2 text-sm font-semibold text-[#315A72] transition hover:bg-[#f0f5f7]"
                                            >
                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4v16m8-8H4"
                                                    />
                                                </svg>

                                                Agregar repuesto
                                            </a>

                                            <div class="flex flex-wrap items-center gap-2">

                                                {{-- Editar --}}
                                                <a
                                                    href="{{ route('preorden.edit', $preorden->id) }}"
                                                    title="Editar preorden"
                                                    aria-label="Editar preorden"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#315A72] text-white transition hover:bg-[#294c61]"
                                                >
                                                    <svg class="block h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                              d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                              d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"/>
                                                    </svg>
                                                </a>

                                                {{-- Cerrar --}}
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

                    <tbody>
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center">
                                <p class="text-sm font-semibold text-slate-700">
                                    No hay preórdenes registradas.
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Utiliza el botón Nueva preorden para registrar el primer presupuesto.
                                </p>
                            </td>
                        </tr>
                    </tbody>

                @endforelse

            </table>

        </div>
    </div>

    {{-- Orientación del flujo --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white px-5 py-4 shadow-sm">
        <p class="text-sm text-slate-600">
            Desde cada preorden se incluyen los repuestos necesarios .
            El cliente debe aprobar o rechazar la preorden desde su portal.
            Cuando la preorden esté aprobada, el administrador podrá crear la orden de trabajo por separado.
        </p>
    </div>

</div>

@endsection
