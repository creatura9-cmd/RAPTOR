@extends('layouts.app')

@section('title', 'Editar entrega')
@section('page-title', 'Editar entrega')

@section('content')

<div class="max-w-4xl mx-auto py-6 space-y-6">

    {{-- Encabezado --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Editar entrega
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Actualiza la información de la entrega registrada.
        </p>
    </div>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $inventario = $entrega->ordenTrabajo?->inventario;
        $moto = $inventario?->moto;
        $cliente = $moto?->cliente;

        $folio = 'RECEPCIÓN #' . str_pad(
            (string) ($inventario?->recepcion ?? ''),
            3,
            '0',
            STR_PAD_LEFT
        );

        $nombreCliente = trim(
            ($cliente?->nombre ?? '') . ' ' .
            ($cliente?->apellido ?? '')
        ) ?: 'Sin cliente';
    @endphp

    {{-- Información de la recepción --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            Información de la recepción
        </h2>

        <input
            type="text"
            value="{{ $folio }} — Moto: {{ $moto?->placa ?? 'Sin placa' }} — Cliente: {{ $nombreCliente }}"
            readonly
            class="w-full rounded-lg border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-600">

        <p class="mt-2 text-xs text-slate-500">
            La recepción asociada a la entrega no se puede modificar.
        </p>

    </div>

    {{-- Información de la entrega --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-slate-800">
            Información de la entrega
        </h2>

        <form
            id="form-editar-entrega"
            action="{{ route('entrega.update', $entrega->id_entrega) }}"
            method="POST"
            class="space-y-5">

            @csrf
            @method('PUT')

            {{-- Mantener la orden de trabajo original --}}
            <input
                type="hidden"
                name="id_ordentrabajo"
                value="{{ $entrega->id_ordentrabajo }}"
            >

            {{-- Fecha de entrega --}}
            <div>
                <label for="fecha_entrega" class="mb-1 block text-sm font-medium text-slate-700">
                    Fecha de entrega
                </label>

                <input
                    type="date"
                    name="fecha_entrega"
                    id="fecha_entrega"
                    value="{{ old('fecha_entrega', $entrega->fecha_entrega) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                @error('fecha_entrega')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Observaciones --}}
            <div>
                <label for="observaciones" class="mb-1 block text-sm font-medium text-slate-700">
                    Observaciones
                </label>

                <textarea
                    name="observaciones"
                    id="observaciones"
                    rows="4"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]"
                    placeholder="Observaciones de la entrega...">{{ old('observaciones', $entrega->observaciones) }}</textarea>

                @error('observaciones')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </form>

    </div>

    {{-- ACCIONES --}}
    <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

        <a
            href="{{ route('entrega.index') }}"
            class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            Cancelar
        </a>

        <button
            type="submit"
            form="form-editar-entrega"
            class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">
            Actualizar entrega
        </button>

    </div>

</div>

@endsection