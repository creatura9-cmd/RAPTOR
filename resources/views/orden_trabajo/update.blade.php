@extends('layouts.app')

@section('title', 'Editar orden de trabajo')
@section('page-title', 'Editar orden de trabajo')

@section('content')

<div class="max-w-4xl mx-auto py-6 space-y-6">

    {{-- Encabezado --}}
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Editar orden de trabajo
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Actualiza la información de la orden de trabajo.
        </p>
    </div>

    {{-- Información de la recepción --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-4 text-lg font-semibold text-slate-800">
            Información de la recepción
        </h2>

        <input
            type="text"
            value="RECEPCIÓN #{{ str_pad($ordenTrabajo->inventario?->recepcion ?? '', 3, '0', STR_PAD_LEFT) }} — Moto: {{ $ordenTrabajo->inventario?->moto?->placa ?? 'Sin placa' }} — Cliente: {{ trim(($ordenTrabajo->inventario?->moto?->cliente?->nombre ?? '') . ' ' . ($ordenTrabajo->inventario?->moto?->cliente?->apellido ?? '')) ?: 'Sin cliente' }}"
            readonly
            class="w-full rounded-lg border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-600">

        <p class="mt-2 text-xs text-slate-500">
            La recepción asociada no se puede modificar.
        </p>

    </div>

    {{-- Formulario de edición --}}
    <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-slate-800">
            Información de la orden de trabajo
        </h2>

        <form
            id="form-editar-orden"
            action="{{ route('orden_trabajo.update', $ordenTrabajo->id) }}"
            method="POST"
            class="space-y-5">

            @csrf
            @method('PUT')

            {{-- Mecánico --}}
            <div>
                <label for="id_mecanico" class="mb-1 block text-sm font-medium text-slate-700">
                    Mecánico
                </label>

                <select
                    name="id_mecanico"
                    id="id_mecanico"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                    <option value="">Seleccione un mecánico</option>

                    @foreach ($mecanicos as $mecanico)
                        <option
                            value="{{ $mecanico->id_mecanico }}"
                            {{ old('id_mecanico', $ordenTrabajo->id_mecanico) == $mecanico->id_mecanico ? 'selected' : '' }}>

                            {{ $mecanico->nombre }}
                            {{ $mecanico->apellido }}
                            - {{ $mecanico->numero_documento }}

                        </option>
                    @endforeach

                </select>

                @error('id_mecanico')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de inicio --}}
            <div>
                <label for="fecha_inicio" class="mb-1 block text-sm font-medium text-slate-700">
                    Fecha de inicio
                </label>

                <input
                    type="date"
                    name="fecha_inicio"
                    id="fecha_inicio"
                    value="{{ old('fecha_inicio', $ordenTrabajo->fecha_inicio) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                @error('fecha_inicio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de finalización --}}
            <div>
                <label for="fecha_fin" class="mb-1 block text-sm font-medium text-slate-700">
                    Fecha de finalización
                </label>

                <input
                    type="date"
                    name="fecha_fin"
                    id="fecha_fin"
                    value="{{ old('fecha_fin', $ordenTrabajo->fecha_fin) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-1 focus:ring-[#315A72]">

                @error('fecha_fin')
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

                    <option value="abierta"
                        {{ old('estado', $ordenTrabajo->estado) == 'abierta' ? 'selected' : '' }}>
                        Abierta
                    </option>

                    <option value="en_proceso"
                        {{ old('estado', $ordenTrabajo->estado) == 'en_proceso' ? 'selected' : '' }}>
                        En proceso
                    </option>

                    <option value="finalizada"
                        {{ old('estado', $ordenTrabajo->estado) == 'finalizada' ? 'selected' : '' }}>
                        Finalizada
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
                    placeholder="Ingrese observaciones de la orden de trabajo...">{{ old('observaciones', $ordenTrabajo->observaciones) }}</textarea>

                @error('observaciones')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </form>

    </div>

    {{-- ACCIONES --}}
    <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

        <a
            href="{{ route('orden_trabajo.index') }}"
            class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            Cancelar
        </a>

        <button
            type="submit"
            form="form-editar-orden"
            class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">
            Actualizar orden de trabajo
        </button>

    </div>

</div>

@endsection