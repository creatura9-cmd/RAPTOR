@extends('layouts.app')

@section('title', 'Editar pago')
@section('page-title', 'Editar pago')

@section('content')

<div class="max-w-4xl mx-auto py-6 space-y-6">

    {{-- Encabezado --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Editar pago
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Actualiza la información del pago registrado.
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
        $preorden = $pago->preorden;
        $inventario = $preorden?->diagnostico?->inventario;
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
            La recepción asociada al pago no se puede modificar.
        </p>


    </div>

    {{-- Información del pago --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-slate-800">
            Información del pago
        </h2>

        <form
            id="form-editar-pago"
            action="{{ route('pago.update', $pago->id) }}"
            method="POST"
            class="space-y-5">

            @csrf
            @method('PUT')

            {{-- Mantener la preorden original --}}
            <input
                type="hidden"
                name="id_preorden"
                value="{{ $pago->id_preorden }}"
            >

            {{-- Mantener la OT si existe --}}
            <input
                type="hidden"
                name="id_ordentrabajo"
                value="{{ $pago->id_ordentrabajo }}"
            >

            {{-- Tipo de pago --}}
            <div>
                <label for="tipo_pago" class="mb-1 block text-sm font-medium text-slate-700">
                    Tipo de pago
                </label>

                <select
                    name="tipo_pago"
                    id="tipo_pago"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                    <option value="">Seleccione un tipo de pago</option>

                    <option value="efectivo"
                        {{ old('tipo_pago', $pago->tipo_pago) == 'efectivo' ? 'selected' : '' }}>
                        Efectivo
                    </option>

                    <option value="transferencia"
                        {{ old('tipo_pago', $pago->tipo_pago) == 'transferencia' ? 'selected' : '' }}>
                        Transferencia
                    </option>

                    <option value="tarjeta"
                        {{ old('tipo_pago', $pago->tipo_pago) == 'tarjeta' ? 'selected' : '' }}>
                        Tarjeta
                    </option>

                </select>

                @error('tipo_pago')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de pago --}}
            <div>
                <label for="fecha_pago" class="mb-1 block text-sm font-medium text-slate-700">
                    Fecha de pago
                </label>

                <input
                    type="date"
                    name="fecha_pago"
                    id="fecha_pago"
                    value="{{ old('fecha_pago', $pago->fecha_pago) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                @error('fecha_pago')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Valor --}}
            <div>
                <label for="valor" class="mb-1 block text-sm font-medium text-slate-700">
                    Valor del pago
                </label>

                <input
                    type="number"
                    name="valor"
                    id="valor"
                    value="{{ old('valor', $pago->valor) }}"
                    min="0.01"
                    step="0.01"
                    required
                    placeholder="Ingrese el valor del pago"
                    @if ($pago->estado === 'confirmado') readonly @endif
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72] {{ $pago->estado === 'confirmado' ? 'bg-slate-100 text-slate-500 cursor-not-allowed' : '' }}">

                @if ($pago->estado === 'confirmado')
                    <p class="mt-1 text-xs text-amber-700">
                        El valor de un pago confirmado no se puede modificar.
                    </p>
                @endif

                @error('valor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label for="estado" class="mb-1 block text-sm font-medium text-slate-700">
                    Estado
                </label>

                <select
                    name="estado"
                    id="estado"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                    <option value="pendiente"
                        {{ old('estado', $pago->estado) == 'pendiente' ? 'selected' : '' }}>
                        Pendiente
                    </option>

                    <option value="confirmado"
                        {{ old('estado', $pago->estado) == 'confirmado' ? 'selected' : '' }}>
                        Confirmado
                    </option>

                    <option value="rechazado"
                        {{ old('estado', $pago->estado) == 'rechazado' ? 'selected' : '' }}>
                        Rechazado
                    </option>

                </select>

                @error('estado')
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
                    placeholder="Ingrese observaciones del pago...">{{ old('observaciones', $pago->observaciones) }}</textarea>

                @error('observaciones')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </form>

    </div>

    {{-- ACCIONES --}}
    <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

        <a
            href="{{ route('pago.index') }}"
            class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            Cancelar
        </a>

        <button
            type="submit"
            form="form-editar-pago"
            class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">
            Actualizar pago
        </button>

    </div>

</div>

@endsection
