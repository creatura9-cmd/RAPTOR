
@extends('layouts.app')

@section('title', 'Nueva orden de trabajo')
@section('page-title', 'Registrar orden de trabajo')

@section('content')

<div class="mx-auto max-w-4xl space-y-5">

    {{-- Encabezado --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            Registrar orden de trabajo
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Registra una orden de trabajo a partir de una recepción con preorden aprobada.
        </p>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-700">
                No fue posible guardar la información.
            </p>

            <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <form action="{{ route('orden_trabajo.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Recepción --}}
            <div>
                <label for="id_preorden"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Recepción
                </label>

                <select
                    name="id_preorden"
                    id="id_preorden"
                    required
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                    <option value="">
                        Seleccione una recepción
                    </option>

                    @foreach ($preordenes as $preorden)
                        @php
                            $diagnostico = $preorden->diagnostico;
                            $inventario = $diagnostico?->inventario;
                            $moto = $inventario?->moto;
                            $cliente = $moto?->cliente;
                        @endphp

                        <option
                            value="{{ $preorden->id }}"
                            @selected(old('id_preorden') == $preorden->id)>

                            RECEPCIÓN #{{ str_pad($inventario?->recepcion ?? '', 3, '0', STR_PAD_LEFT) }}
                            — Moto: {{ $moto->placa ?? 'Sin placa' }}
                            — Cliente: {{ trim(($cliente->nombre ?? '') . ' ' . ($cliente->apellido ?? '')) ?: 'Sin cliente' }}
                            — Total: ${{ number_format((float) $preorden->total, 0, ',', '.') }}

                        </option>
                    @endforeach

                </select>

                @error('id_preorden')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                @if ($preordenes->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        No hay recepciones con preórdenes aprobadas disponibles para registrar una orden de trabajo.
                    </p>
                @endif
            </div>

            {{-- Mecánico --}}
            <div>
                <label for="id_mecanico"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Mecánico
                </label>

                <select
                    name="id_mecanico"
                    id="id_mecanico"
                    required
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                    <option value="">Seleccione un mecánico</option>

                    @foreach ($mecanicos as $mecanico)
                        <option
                            value="{{ $mecanico->id_mecanico }}"
                            @selected(old('id_mecanico') == $mecanico->id_mecanico)>

                            {{ $mecanico->nombre }}
                            {{ $mecanico->apellido }}
                            — {{ $mecanico->numero_documento }}

                        </option>
                    @endforeach

                </select>

                @error('id_mecanico')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Fecha de inicio --}}
            <div>
                <label for="fecha_inicio"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Fecha de inicio
                </label>

                <input
                    type="date"
                    name="fecha_inicio"
                    id="fecha_inicio"
                    value="{{ old('fecha_inicio', date('Y-m-d')) }}"
                    required
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                @error('fecha_inicio')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Fecha de finalización --}}
            <div>
                <label for="fecha_fin"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Fecha de finalización
                </label>

                <input
                    type="date"
                    name="fecha_fin"
                    id="fecha_fin"
                    value="{{ old('fecha_fin') }}"
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                @error('fecha_fin')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label for="estado"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Estado
                </label>

                <select
                    name="estado"
                    id="estado"
                    required
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                    <option value="">Seleccione un estado</option>

                    <option value="abierta"
                        @selected(old('estado', 'abierta') == 'abierta')>
                        Abierta
                    </option>

                    <option value="en_proceso"
                        @selected(old('estado') == 'en_proceso')>
                        En proceso
                    </option>

                    <option value="finalizada"
                        @selected(old('estado') == 'finalizada')>
                        Finalizada
                    </option>

                </select>

                @error('estado')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Observaciones --}}
            <div>
                <label for="observaciones"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Observaciones
                </label>

                <textarea
                    name="observaciones"
                    id="observaciones"
                    rows="4"
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20"
                    placeholder="Ingrese observaciones de la orden de trabajo...">{{ old('observaciones') }}</textarea>

                @error('observaciones')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

                <a
                    href="{{ route('orden_trabajo.index') }}"
                    class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">

                    Registrar orden de trabajo

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
