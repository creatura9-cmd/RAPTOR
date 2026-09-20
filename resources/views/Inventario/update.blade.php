@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Editar inventario
        </h1>

        <p class="text-gray-600">
            Actualiza la información de la inspección de la motocicleta.
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">

        <form action="{{ route('inventario.update', $inventario->id) }}" method="POST">

            @csrf
            @method('PUT')

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
                            {{ old('id_moto', $inventario->id_moto) == $moto->id ? 'selected' : '' }}>

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

            {{-- Fecha --}}
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

            {{-- Condición general --}}
            <div class="mb-4">
                <label for="condicion_general"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Condición general
                </label>

                <select name="condicion_general"
                        id="condicion_general"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">

                    <option value="bueno"
                        {{ old('condicion_general', $inventario->condicion_general) == 'bueno' ? 'selected' : '' }}>
                        Bueno
                    </option>

                    <option value="regular"
                        {{ old('condicion_general', $inventario->condicion_general) == 'regular' ? 'selected' : '' }}>
                        Regular
                    </option>

                    <option value="malo"
                        {{ old('condicion_general', $inventario->condicion_general) == 'malo' ? 'selected' : '' }}>
                        Malo
                    </option>

                </select>

                @error('condicion_general')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Estado del inventario --}}
            <div class="mb-6">
                <label for="estado_inventario"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Estado del inventario
                </label>

                <select name="estado_inventario"
                        id="estado_inventario"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">

                    <option value="pendiente"
                        {{ old('estado_inventario', $inventario->estado_inventario) == 'pendiente' ? 'selected' : '' }}>
                        Pendiente
                    </option>

                    <option value="en_proceso"
                        {{ old('estado_inventario', $inventario->estado_inventario) == 'en_proceso' ? 'selected' : '' }}>
                        En proceso
                    </option>

                    <option value="finalizado"
                        {{ old('estado_inventario', $inventario->estado_inventario) == 'finalizado' ? 'selected' : '' }}>
                        Finalizado
                    </option>

                </select>

                @error('estado_inventario')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex items-center gap-3">

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Actualizar inventario
                </button>

                <a href="{{ route('inventario.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection