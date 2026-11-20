@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Nuevo inventario
        </h1>

        <p class="text-gray-600">
            Registra la inspección inicial de una motocicleta.
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('inventario.store') }}" method="POST">

            @csrf

            {{-- Moto --}}
            <div class="mb-4">
                <label for="id_moto"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Moto
                </label>

                <select name="id_moto"
                        id="id_moto"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">

                    <option value="">
                        Seleccione una moto
                    </option>

                    @foreach ($motos as $moto)

                        <option value="{{ $moto->id }}"
                            {{ old('id_moto') == $moto->id ? 'selected' : '' }}>

                            {{ $moto->placa }}
                            -
                            {{ $moto->marca->nombre_marca }}
                            -
                            {{ $moto->modelo }}
                            -
                            {{ $moto->cliente->nombre }}
                            {{ $moto->cliente->apellido }}

                        </option>

                    @endforeach

                </select>

                @error('id_moto')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Mecánico --}}
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
                            {{ old('id_mecanico') == $mecanico->id_mecanico ? 'selected' : '' }}>

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
                          placeholder="Describe el estado general de la motocicleta...">{{ old('descripcion') }}</textarea>

                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Fecha --}}
            <div class="mb-4">
                <label for="fecha_registro"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha de registro
                </label>

                <input type="datetime-local"
                       name="fecha_registro"
                       id="fecha_registro"
                       value="{{ old('fecha_registro', now()->format('Y-m-d\TH:i')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">

                @error('fecha_registro')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Condición general --}}
            <div class="mb-4">
                <label for="condicion_general"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Condición general
                </label>

                <select name="condicion_general"
                        id="condicion_general"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">

                    <option value="">
                        Seleccione una condición
                    </option>

                    <option value="bueno"
                        {{ old('condicion_general') == 'bueno' ? 'selected' : '' }}>
                        Bueno
                    </option>

                    <option value="regular"
                        {{ old('condicion_general') == 'regular' ? 'selected' : '' }}>
                        Regular
                    </option>

                    <option value="malo"
                        {{ old('condicion_general') == 'malo' ? 'selected' : '' }}>
                        Malo
                    </option>

                </select>

                @error('condicion_general')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-2">

                <a href="{{ route('inventario.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-[#dce8ed] bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-[#f0f5f7]">
                    Cancelar
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]">
                    Guardar inventario
                </button>

            </div>

        </form>

    </div>

</div>

@endsection