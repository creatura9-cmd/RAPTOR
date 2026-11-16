@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-lg p-6">

        <h2 class="text-2xl font-bold text-gray-700 mb-6">
            Editar Repuesto de Preorden
        </h2>

        <form action="{{ route('preorden_repuesto.update', $preordenRepuesto->id) }}" method="POST">

            @csrf
            @method('PUT')

            {{-- Preorden --}}
            <div class="mb-4">

                <label for="id_preorden"
                       class="block text-gray-700 font-bold mb-2">
                    Preorden
                </label>

                <select name="id_preorden"
                        id="id_preorden"
                        class="w-full border border-gray-300 rounded px-3 py-2">

                    <option value="">
                        Seleccione una preorden
                    </option>

                    @foreach($preordenes as $preorden)

                        <option value="{{ $preorden->id }}"
                            {{ old('id_preorden', $preordenRepuesto->id_preorden) == $preorden->id ? 'selected' : '' }}>

                            Preorden #{{ $preorden->id }}

                        </option>

                    @endforeach

                </select>

                @error('id_preorden')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Repuesto --}}
            <div class="mb-4">

                <label for="id_repuesto"
                       class="block text-gray-700 font-bold mb-2">
                    Repuesto
                </label>

                <select name="id_repuesto"
                        id="id_repuesto"
                        class="w-full border border-gray-300 rounded px-3 py-2">

                    <option value="">
                        Seleccione un repuesto
                    </option>

                    @foreach($repuestos as $repuesto)

                        <option value="{{ $repuesto->id }}"
                            {{ old('id_repuesto', $preordenRepuesto->id_repuesto) == $repuesto->id ? 'selected' : '' }}>

                            {{ $repuesto->nombre }}

                            @if($repuesto->marca)
                                - {{ $repuesto->marca }}
                            @endif

                            - $ {{ number_format($repuesto->precio, 2, ',', '.') }}

                        </option>

                    @endforeach

                </select>

                @error('id_repuesto')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Cantidad --}}
            <div class="mb-4">

                <label for="cantidad"
                       class="block text-gray-700 font-bold mb-2">
                    Cantidad
                </label>

                <input type="number"
                       name="cantidad"
                       id="cantidad"
                       min="1"
                       value="{{ old('cantidad', $preordenRepuesto->cantidad) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       placeholder="Ingrese la cantidad">

                @error('cantidad')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Precio unitario --}}
            <div class="mb-4">

                <label for="precio_unitario"
                       class="block text-gray-700 font-bold mb-2">
                    Precio unitario
                </label>

                <input type="number"
                       name="precio_unitario"
                       id="precio_unitario"
                       step="0.01"
                       min="0"
                       value="{{ old('precio_unitario', $preordenRepuesto->precio_unitario) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       placeholder="0.00">

                @error('precio_unitario')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Subtotal --}}
            <div class="mb-6">

                <label for="subtotal"
                       class="block text-gray-700 font-bold mb-2">
                    Subtotal
                </label>

                <input type="number"
                       name="subtotal"
                       id="subtotal"
                       step="0.01"
                       min="0"
                       value="{{ old('subtotal', $preordenRepuesto->subtotal) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       placeholder="0.00">

                @error('subtotal')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Botones --}}
            <div class="flex gap-3">

                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Actualizar
                </button>

                <a href="{{ route('preorden_repuesto.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection