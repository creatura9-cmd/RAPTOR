@extends('layouts.app')

@section('content')

<div class="p-6">

    {{-- Encabezado --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Editar inventario
        </h1>

        <p class="text-gray-600">
            Actualiza la información de la inspección de la motocicleta.
        </p>
    </div>

    {{-- Formulario --}}
    <div class="bg-white rounded-lg shadow p-6">

        {{-- Información fija de la recepción --}}
        <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-3">

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Número de recepción
                </p>

                <p class="text-sm font-semibold text-[#315A72]">
                    RECEPCIÓN # {{ str_pad($inventario->recepcion, 3, '0', STR_PAD_LEFT) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Placa
                </p>

                <p class="text-sm font-semibold text-gray-800">
                    {{ $inventario->moto->placa }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-500">
                    Propietario
                </p>

                <p class="text-sm font-semibold text-gray-800">
                    {{ $inventario->moto->cliente->nombre }}
                    {{ $inventario->moto->cliente->apellido }}
                </p>
            </div>

        </div>

        <form action="{{ route('inventario.update', $inventario->id) }}"
              method="POST">

            @csrf
            @method('PUT')
            <input type="hidden" name="id_moto" value="{{ $inventario->id_moto }}">

            {{-- Mecánico responsable --}}
            <div class="mb-4">
                <label for="id_mecanico"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Mecánico responsable
                </label>

                <select name="id_mecanico"
                        id="id_mecanico"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">

                    <option value="">
                        Seleccione un mecánico
                    </option>

                    @foreach ($mecanicos as $mecanico)
                        <option value="{{ $mecanico->id_mecanico }}"
                            {{ old('id_mecanico', $inventario->id_mecanico) == $mecanico->id_mecanico ? 'selected' : '' }}>

                            {{ $mecanico->nombre }}
                            {{ $mecanico->apellido }}
                            -
                            {{ $mecanico->numero_documento }}

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
                    Descripción
                </label>

                <textarea name="descripcion"
                          id="descripcion"
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2"
                          placeholder="Describe el estado general de la motocicleta...">{{ old('descripcion', $inventario->descripcion) }}</textarea>

                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Fecha de registro --}}
            <div class="mb-4">
                <label for="fecha_registro"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha de registro
                </label>

                <input type="datetime-local"
                       name="fecha_registro"
                       id="fecha_registro"
                       value="{{ old('fecha_registro', \Carbon\Carbon::parse($inventario->fecha_registro)->format('Y-m-d\TH:i')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">

                @error('fecha_registro')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

                        {{-- Condición general: solo lectura --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Condición general
                            </label>

                            <div class="w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700">
                                {{ ucfirst($inventario->condicion_general ?? 'No registrada') }}
                            </div>

                            <p class="mt-1 text-xs text-gray-500">
                                La condición general se registra al crear la recepción y no puede modificarse posteriormente.
                            </p>
                        </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-2">

                <a href="{{ route('inventario.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-[#dce8ed] bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-[#f0f5f7]">
                    Cancelar
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]">
                    Actualizar inventario
                </button>

            </div>

        </form>

    </div>

</div>

@endsection