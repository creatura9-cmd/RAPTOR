@extends('layouts.app')

@section('title', 'Nueva entrega')
@section('page-title', 'Nueva entrega')

@section('content')

<div class="mx-auto max-w-3xl">

    <div class="rounded-lg bg-white p-6 shadow-sm">

        {{-- Encabezado --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">
                Registrar entrega
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Registre la entrega de la motocicleta al cliente.
            </p>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('entrega.store') }}" method="POST">

            @csrf

            {{-- Orden de trabajo disponible --}}
            <div class="mb-5">

                <label
                    for="id_ordentrabajo"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Recepción
                </label>

                <select
                    name="id_ordentrabajo"
                    id="id_ordentrabajo"
                    class="w-full rounded-lg border-gray-300 focus:border-[#315A72] focus:ring-[#315A72]"
                    required
                >
                    <option value="">
                        Seleccione una Recepción
                    </option>

                    @foreach ($ordenesDisponibles as $orden)
                        @php
                            $recepcion = $orden->preorden?->diagnostico?->inventario?->recepcion
                                ?? $orden->inventario?->recepcion;
                        @endphp

                        <option
                            value="{{ $orden->id }}"
                            @selected(old('id_ordentrabajo') == $orden->id)
                        >
                            {{ $recepcion
                                ? 'RECEPCIÓN # ' . str_pad((string) $recepcion, 3, '0', STR_PAD_LEFT)
                                : 'RECEPCIÓN' }}
                            — {{ $orden->inventario?->moto?->placa ?? 'Sin placa' }}
                            — {{ trim(($orden->inventario?->moto?->cliente?->nombre ?? '') . ' ' . ($orden->inventario?->moto?->cliente?->apellido ?? '')) ?: 'Sin cliente' }}
                        </option>
                    @endforeach
                </select>

                @error('id_ordentrabajo')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                @if ($ordenesDisponibles->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        No hay órdenes de trabajo disponibles para entregar.
                    </p>
                @endif

            </div>

            {{-- Fecha de entrega --}}
            <div class="mb-5">

                <label
                    for="fecha_entrega"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Fecha de entrega
                </label>

                <input
                    type="date"
                    name="fecha_entrega"
                    id="fecha_entrega"
                    value="{{ old('fecha_entrega', date('Y-m-d')) }}"
                    class="w-full rounded-lg border-gray-300 focus:border-[#315A72] focus:ring-[#315A72]"
                    required
                >

                @error('fecha_entrega')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Observaciones --}}
            <div class="mb-6">

                <label
                    for="observaciones"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Observaciones
                </label>

                <textarea
                    name="observaciones"
                    id="observaciones"
                    rows="4"
                    class="w-full rounded-lg border-gray-300 focus:border-[#315A72] focus:ring-[#315A72]"
                    placeholder="Observaciones de la entrega"
                >{{ old('observaciones') }}</textarea>

                @error('observaciones')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('entrega.index') }}"
                    class="rounded-lg bg-gray-200 px-4 py-2 text-gray-700 transition hover:bg-gray-300"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#315A72] px-4 py-2 text-white transition hover:bg-[#294c61]"
                >
                    Registrar entrega
                </button>

            </div>

        </form>

    </div>

</div>

@endsection