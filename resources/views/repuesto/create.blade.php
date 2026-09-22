@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">

        <h2 class="text-2xl font-bold text-gray-700 mb-6">
            Registrar Repuesto
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('repuesto.store') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label for="nombre" class="block text-gray-700 font-semibold mb-2">
                    Nombre
                </label>

                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2"
                       required>

            </div>

            <div class="mb-4">

                <label for="marca" class="block text-gray-700 font-semibold mb-2">
                    Marca
                </label>

                <input type="text"
                       name="marca"
                       id="marca"
                       value="{{ old('marca') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">

            </div>

            <div class="mb-4">

                <label for="categoria" class="block text-gray-700 font-semibold mb-2">
                    Categoría
                </label>

                <select name="categoria"
                        id="categoria"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        required>

                    <option value="">Seleccione una categoría</option>

                    <option value="motor" {{ old('categoria') == 'motor' ? 'selected' : '' }}>
                        Motor
                    </option>

                    <option value="transmision" {{ old('categoria') == 'transmision' ? 'selected' : '' }}>
                        Transmisión
                    </option>

                    <option value="frenos" {{ old('categoria') == 'frenos' ? 'selected' : '' }}>
                        Frenos
                    </option>

                    <option value="electrico" {{ old('categoria') == 'electrico' ? 'selected' : '' }}>
                        Eléctrico
                    </option>

                    <option value="combustible" {{ old('categoria') == 'combustible' ? 'selected' : '' }}>
                        Combustible
                    </option>

                    <option value="suspension" {{ old('categoria') == 'suspension' ? 'selected' : '' }}>
                        Suspensión
                    </option>

                    <option value="direccion" {{ old('categoria') == 'direccion' ? 'selected' : '' }}>
                        Dirección
                    </option>

                    <option value="ruedas" {{ old('categoria') == 'ruedas' ? 'selected' : '' }}>
                        Ruedas
                    </option>

                    <option value="accesorios" {{ old('categoria') == 'accesorios' ? 'selected' : '' }}>
                        Accesorios
                    </option>

                </select>

            </div>

            <div class="mb-6">

                <label for="precio" class="block text-gray-700 font-semibold mb-2">
                    Precio
                </label>

                <input type="number"
                       name="precio"
                       id="precio"
                       value="{{ old('precio') }}"
                       min="0"
                       step="0.01"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2"
                       required>

            </div>

            <div class="flex justify-between">

                <a href="{{ route('repuesto.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Cancelar
                </a>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Guardar Repuesto
                </button>

            </div>

        </form>

    </div>

</div>

@endsection