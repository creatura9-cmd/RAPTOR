@extends('layouts.app')
@section('title', 'Editar preorden')
@section('page-title', 'Editar preorden')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- Encabezado --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Editar preorden
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Administra el presupuesto y los repuestos asociados a esta recepción.
        </p>
    </div>

    {{-- Información de la recepción --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            Información de la recepción
        </h2>

        <input
            type="text"
            value="RECEPCIÓN #{{ str_pad($preorden->diagnostico?->inventario?->recepcion ?? '', 3, '0', STR_PAD_LEFT) }} — Moto: {{ $preorden->diagnostico?->inventario?->moto?->placa ?? 'Sin placa' }} — Cliente: {{ trim(($preorden->diagnostico?->inventario?->moto?->cliente?->nombre ?? '') . ' ' . ($preorden->diagnostico?->inventario?->moto?->cliente?->apellido ?? '')) ?: 'Sin cliente' }}"
            readonly
            class="w-full rounded-lg border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-600">

    </div>

    {{-- Formulario de edición --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-slate-800">
            Información de la preorden
        </h2>

        <form
            id="form-editar-preorden"
            action="{{ route('preorden.update', $preorden->id) }}"
            method="POST"
            class="space-y-5">

            @csrf
            @method('PUT')

            {{-- Descripción --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Descripción del servicio
                </label>

                <textarea
                    name="descripcion"
                    rows="4"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">{{ old('descripcion', $preorden->descripcion) }}</textarea>

                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mano de obra --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Mano de obra
                </label>

                <input
                    type="number"
                    name="mano_obra"
                    min="0"
                    step="0.01"
                    required
                    value="{{ old('mano_obra', $preorden->mano_obra) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                @error('mano_obra')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Estado de la preorden
                </label>

                <select
                    name="estado"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                    <option value="pendiente"
                        {{ old('estado', $preorden->estado) == 'pendiente' ? 'selected' : '' }}>
                        Pendiente
                    </option>

                    <option value="aprobada"
                        {{ old('estado', $preorden->estado) == 'aprobada' ? 'selected' : '' }}>
                        Aprobada
                    </option>

                    <option value="rechazada"
                        {{ old('estado', $preorden->estado) == 'rechazada' ? 'selected' : '' }}>
                        Rechazada
                    </option>

                </select>

                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </form>
    </div>

    {{-- Repuestos asociados --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-3">

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Repuestos asociados
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Consulta y agrega los repuestos de esta preorden.
                </p>
            </div>

            <a
                href="{{ route('preorden.create', ['id_preorden' => $preorden->id]) }}"
                class="rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]">
                + Agregar repuesto
            </a>

        </div>

        {{-- Desplegable de repuestos --}}
        <div class="mt-4">

            <details class="group rounded-lg border border-slate-200">

                <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 text-sm font-medium text-slate-700">

                    <span>
                        Ver repuestos ({{ $preorden->repuestos->count() }})
                    </span>

                    <svg
                        class="h-4 w-4 text-slate-500 transition group-open:rotate-180"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />

                    </svg>

                </summary>

                <div class="border-t border-slate-200">

                    @forelse ($preorden->repuestos as $detalle)

                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0">

                            <div>
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $detalle->repuesto?->nombre ?? 'Repuesto' }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    Cantidad: {{ $detalle->cantidad }}
                                </p>
                            </div>

                            <p class="text-sm font-semibold text-slate-700">
                                ${{ number_format($detalle->subtotal, 0, ',', '.') }}
                            </p>

                        </div>

                    @empty

                        <p class="px-4 py-4 text-sm text-slate-500">
                            Esta preorden todavía no tiene repuestos asociados.
                        </p>

                    @endforelse

                </div>

            </details>

        </div>

    </div>

    {{-- Resumen económico --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

        {{-- Mano de obra --}}
        <div class="rounded-lg border border-[#dce8ed] bg-white px-4 py-3 shadow-sm">

            <p class="text-xs font-medium text-slate-500">
                Mano de obra
            </p>

            <p class="mt-1 text-base font-semibold text-slate-800">
                ${{ number_format($preorden->mano_obra ?? 0, 0, ',', '.') }}
            </p>

        </div>

        {{-- Total repuestos --}}
        <div class="rounded-lg border border-[#dce8ed] bg-white px-4 py-3 shadow-sm">

            <p class="text-xs font-medium text-slate-500">
                Total repuestos
            </p>

            <p class="mt-1 text-base font-semibold text-slate-800">
                ${{ number_format($preorden->repuestos->sum('subtotal'), 0, ',', '.') }}
            </p>

        </div>

        {{-- Total general --}}
        <div class="rounded-lg border border-[#dce8ed] bg-[#f0f5f7] px-4 py-3 shadow-sm">

            <p class="text-xs font-medium text-slate-500">
                Total general
            </p>

            <p class="mt-1 text-base font-bold text-[#315A72]">
                ${{ number_format(($preorden->mano_obra ?? 0) + $preorden->repuestos->sum('subtotal'), 0, ',', '.') }}
            </p>

        </div>

    </div>

    {{-- ACCIONES --}}
    <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

        <a
            href="{{ route('preorden.index') }}"
            class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            Cancelar
        </a>

        <button
            type="submit"
            form="form-editar-preorden"
            class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">

            Actualizar preorden

        </button>

    </div>

</div>

@endsection