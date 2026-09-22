@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

<div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">

<div class="mb-6">

<h2 class="text-2xl font-bold text-gray-700">
    Editar Diagnóstico
</h2>

</div>

<form action="{{ route('diagnostico.update', $diagnostico->id) }}" method="POST">

@csrf
@method('PUT')

{{-- Inventario --}}

<div class="mb-4">

    <label for="id_inventario"
           class="block text-sm font-medium text-gray-700 mb-1">

        Inventario

    </label>

    <select name="id_inventario"
            id="id_inventario"
            class="w-full border border-gray-300 rounded-lg px-4 py-2"
            required>

        <option value="">
            Seleccione un inventario
        </option>

        @foreach ($inventarios as $inventario)

            <option value="{{ $inventario->id }}"
                {{ old('id_inventario', $diagnostico->id_inventario) == $inventario->id ? 'selected' : '' }}>

                Inventario #{{ $inventario->id }}
                -
                Moto: {{ $inventario->moto->placa ?? 'Sin placa' }}

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
            class="w-full border border-gray-300 rounded-lg px-4 py-2"
            required>

        <option value="">
            Seleccione un mecánico
        </option>

        @foreach ($mecanicos as $mecanico)

            <option value="{{ $mecanico->id_mecanico }}"
                {{ old('id_mecanico', $diagnostico->id_mecanico) == $mecanico->id_mecanico ? 'selected' : '' }}>

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
              class="w-full border border-gray-300 rounded-lg px-4 py-2"
              required>{{ old('descripcion', $diagnostico->descripcion) }}</textarea>

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
           value="{{ old('fecha_diagnostico', $diagnostico->fecha_diagnostico) }}"
           class="w-full border border-gray-300 rounded-lg px-4 py-2"
           required>

    @error('fecha_diagnostico')

        <p class="text-red-600 text-sm mt-1">
            {{ $message }}
        </p>

    @enderror

</div>

{{-- Estado --}}

<div class="mb-6">

    <label for="estado"
           class="block text-sm font-medium text-gray-700 mb-1">

        Estado

    </label>

    <select name="estado"
            id="estado"
            class="w-full border border-gray-300 rounded-lg px-4 py-2"
            required>

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

        <p class="text-red-600 text-sm mt-1">
            {{ $message }}
        </p>

    @enderror

</div>

{{-- Botones --}}

<div class="flex items-center gap-3">

    <button type="submit"
            class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition">

        Actualizar

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
