@extends('layouts.app')

@section('title', 'Editar diagnóstico')
@section('page-title', 'Editar diagnóstico')

@section('content')

<div class="max-w-4xl mx-auto py-6 space-y-6">

    {{-- Encabezado --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Editar diagnóstico
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Actualiza la información del diagnóstico asociado a esta recepción.
        </p>
    </div>

    {{-- Información de la recepción --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            Información de la recepción
        </h2>

        <input
            type="text"
            value="RECEPCIÓN #{{ str_pad($diagnostico->inventario?->recepcion ?? '', 3, '0', STR_PAD_LEFT) }} — Moto: {{ $diagnostico->inventario?->moto?->placa ?? 'Sin placa' }} — Cliente: {{ trim(($diagnostico->inventario?->moto?->cliente?->nombre ?? '') . ' ' . ($diagnostico->inventario?->moto?->cliente?->apellido ?? '')) ?: 'Sin cliente' }}"
            readonly
            class="w-full rounded-lg border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-600">

        <p class="mt-2 text-xs text-slate-500">
            La recepción asociada no se puede modificar.
        </p>

    </div>

    {{-- Formulario de edición --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-slate-800">
            Información del diagnóstico
        </h2>

        <form
            id="form-editar-diagnostico"
            action="{{ route('diagnostico.update', $diagnostico->id) }}"
            method="POST"
            class="space-y-5">

            @csrf
            @method('PUT')

            {{-- Inventario fijo --}}
            <input
                type="hidden"
                name="id_inventario"
                value="{{ $diagnostico->id_inventario }}">

            {{-- Mecánico --}}
            <div>
                <label
                    for="id_mecanico"
                    class="mb-1 block text-sm font-medium text-slate-700">
                    Mecánico
                </label>

                <select
                    name="id_mecanico"
                    id="id_mecanico"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                    <option value="">
                        Seleccione un mecánico
                    </option>

                    @foreach ($mecanicos as $mecanico)

                        <option
                            value="{{ $mecanico->id_mecanico }}"
                            {{ old('id_mecanico', $diagnostico->id_mecanico) == $mecanico->id_mecanico ? 'selected' : '' }}>

                            {{ $mecanico->nombre }}
                            {{ $mecanico->apellido }}

                        </option>

                    @endforeach

                </select>

                @error('id_mecanico')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div>
                <label
                    for="descripcion"
                    class="mb-1 block text-sm font-medium text-slate-700">
                    Descripción del diagnóstico
                </label>

                <textarea
                    name="descripcion"
                    id="descripcion"
                    rows="4"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">{{ old('descripcion', $diagnostico->descripcion) }}</textarea>

                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Fecha --}}
            <div>
                <label
                    for="fecha_diagnostico"
                    class="mb-1 block text-sm font-medium text-slate-700">
                    Fecha del diagnóstico
                </label>

                <input
                    type="date"
                    name="fecha_diagnostico"
                    id="fecha_diagnostico"
                    value="{{ old('fecha_diagnostico', $diagnostico->fecha_diagnostico) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                @error('fecha_diagnostico')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label
                    for="estado"
                    class="mb-1 block text-sm font-medium text-slate-700">
                    Estado del diagnóstico
                </label>

                <select
                    name="estado"
                    id="estado"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                    <option value="pendiente"
                        {{ old('estado', $diagnostico->estado) == 'pendiente' ? 'selected' : '' }}>
                        Pendiente
                    </option>

                    <option value="en_proceso"
                        {{ old('estado', $diagnostico->estado) == 'en_proceso' ? 'selected' : '' }}>
                        En proceso
                    </option>

                    <option value="finalizado"
                        {{ old('estado', $diagnostico->estado) == 'finalizado' ? 'selected' : '' }}>
                        Finalizado
                    </option>

                    <option value="cancelado"
                        {{ old('estado', $diagnostico->estado) == 'cancelado' ? 'selected' : '' }}>
                        Cancelado
                    </option>

                </select>

                @error('estado')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </form>

    </div>

    {{-- ACCIONES --}}
    <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

        <a
            href="{{ route('diagnostico.index') }}"
            class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            Cancelar
        </a>

        <button
            type="submit"
            form="form-editar-diagnostico"
            class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">

            Actualizar diagnóstico

        </button>

    </div>

</div>

@endsection
