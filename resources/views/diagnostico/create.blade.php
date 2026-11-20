@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">

        <div class="mb-6">

            <h2 class="text-2xl font-bold text-gray-700">
                Nuevo Diagnóstico
            </h2>

        </div>

        <form action="{{ route('diagnostico.store') }}" method="POST">

            @csrf

            {{-- Recepción --}}
            <div class="mb-4">

                <label for="id_inventario"
                       class="block text-sm font-medium text-gray-700 mb-1">

                    Recepción

                </label>

                <select name="id_inventario"
                        id="id_inventario"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                        required>

                    <option value="">
                        Seleccione una recepción
                    </option>

                    @foreach ($inventarios as $inventario)

                        <option value="{{ $inventario->id }}"
                            {{ old('id_inventario') == $inventario->id ? 'selected' : '' }}>

                            {{ 'RECEPCIÓN # ' . str_pad($inventario->recepcion, 3, '0', STR_PAD_LEFT) }}
                            - Moto: {{ $inventario->moto->placa ?? 'Sin placa' }}

                        </option>

                    @endforeach

                </select>

                @error('id_inventario')

                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- Mecánico --}}
            <div class="mb-4">

                <label for="id_mecanico"
                       class="block text-sm font-medium text-gray-700 mb-1">

                    Mecánico

                </label>

                <select name="id_mecanico"
                        id="id_mecanico"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                        required>

                    <option value="">
                        Seleccione un mecánico
                    </option>

                    @foreach ($mecanicos as $mecanico)

                        <option value="{{ $mecanico->id_mecanico }}"
                            {{ old('id_mecanico') == $mecanico->id_mecanico ? 'selected' : '' }}>

                            {{ $mecanico->nombre }}
                            {{ $mecanico->apellido }}

                        </option>

                    @endforeach

                </select>

                @error('id_mecanico')

                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- Descripción --}}
            <div class="mb-4">

                <label for="descripcion"
                       class="block text-sm font-medium text-gray-700 mb-1">

                    Descripción del diagnóstico

                </label>

                <textarea name="descripcion"
                          id="descripcion"
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                          required>{{ old('descripcion') }}</textarea>

                @error('descripcion')

                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- Fecha --}}
            <div class="mb-4">

                <label for="fecha_diagnostico"
                       class="block text-sm font-medium text-gray-700 mb-1">

                    Fecha del diagnóstico

                </label>

                <input type="date"
                       name="fecha_diagnostico"
                       id="fecha_diagnostico"
                       value="{{ old('fecha_diagnostico', date('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                       required>

                @error('fecha_diagnostico')

                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>
            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3">

                <button type="submit"
                        class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition">

                    Guardar

                </button>

                <a href="{{ route('diagnostico.index') }}"
                   class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-300 transition">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
