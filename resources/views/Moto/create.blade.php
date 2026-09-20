@extends('layouts.app')

@section('title', 'Nueva moto')

@section('content')

<div class="space-y-6">

```
{{-- Encabezado --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Registrar nueva moto
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Registra una motocicleta y asígnala a un cliente.
        </p>
    </div>

</div>


{{-- Mensaje de error general --}}
@if ($errors->any())

    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

        <p class="font-semibold">
            Se encontraron algunos errores:
        </p>

        <ul class="mt-2 list-disc space-y-1 pl-5">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- Formulario --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="p-6">

        <form
            action="{{ route('moto.store') }}"
            method="POST"
            class="space-y-6"
        >

            @csrf


            {{-- Cliente --}}
            <div>

                <label
                    for="id_cliente"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Cliente
                </label>

                <select
                    name="id_cliente"
                    id="id_cliente"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                >

                    <option value="">
                        Seleccione un cliente
                    </option>

                    @foreach ($clientes as $cliente)

                        <option
                            value="{{ $cliente->id }}"
                            {{ old('id_cliente') == $cliente->id ? 'selected' : '' }}
                        >
                            {{ $cliente->nombre }}
                            {{ $cliente->apellido }}
                            - {{ $cliente->numero_documento }}
                        </option>

                    @endforeach

                </select>

                @error('id_cliente')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Marca --}}
            <div>

                <label
                    for="id_marca"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Marca
                </label>

                <select
                    name="id_marca"
                    id="id_marca"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                >

                    <option value="">
                        Seleccione una marca
                    </option>

                    @foreach ($marcas as $marca)

                        <option
                            value="{{ $marca->id }}"
                            {{ old('id_marca') == $marca->id ? 'selected' : '' }}
                        >
                            {{ $marca->nombre_marca }}
                        </option>

                    @endforeach

                </select>

                @error('id_marca')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Modelo --}}
            <div>

                <label
                    for="modelo"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Modelo
                </label>

                <input
                    type="text"
                    name="modelo"
                    id="modelo"
                    value="{{ old('modelo') }}"
                    placeholder="Ejemplo: FZ"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                >

                @error('modelo')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Año --}}
            <div>

                <label
                    for="anio"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Año
                </label>

                <input
                    type="number"
                    name="anio"
                    id="anio"
                    value="{{ old('anio') }}"
                    min="1900"
                    max="{{ date('Y') }}"
                    placeholder="Ejemplo: 2024"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                >

                @error('anio')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Placa --}}
            <div>

                <label
                    for="placa"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Placa
                </label>

                <input
                    type="text"
                    name="placa"
                    id="placa"
                    value="{{ old('placa') }}"
                    placeholder="Ejemplo: ABC12D"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm uppercase shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                >

                @error('placa')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">

                <a
                    href="{{ route('moto.index') }}"
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-800"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700"
                >
                    Guardar moto
                </button>

            </div>

        </form>

    </div>

</div>
```

</div>

@endsection


