@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<div class="space-y-6">

    {{-- Encabezado --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Editar Cliente
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Actualiza la información del cliente.
        </p>
    </div>

    {{-- Formulario --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form
            action="{{ route('cliente.update', $cliente->id) }}"
            method="POST"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            {{-- Nombre y apellido --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label
                        for="nombre"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('nombre', $cliente->nombre) }}"
                        maxlength="100"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        placeholder="Ingrese el nombre"
                    >

                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="apellido"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Apellido
                    </label>

                    <input
                        type="text"
                        name="apellido"
                        id="apellido"
                        value="{{ old('apellido', $cliente->apellido) }}"
                        maxlength="100"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        placeholder="Ingrese el apellido"
                    >

                    @error('apellido')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Documento --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label
                        for="tipo_documento"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tipo de documento
                    </label>

                    <select
                        name="tipo_documento"
                        id="tipo_documento"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                    >
                        <option value="">Seleccione un tipo</option>

                        <option value="CC" {{ old('tipo_documento', $cliente->tipo_documento) == 'CC' ? 'selected' : '' }}>
                            Cédula de Ciudadanía (CC)
                        </option>

                        <option value="CE" {{ old('tipo_documento', $cliente->tipo_documento) == 'CE' ? 'selected' : '' }}>
                            Cédula de Extranjería (CE)
                        </option>

                        <option value="TI" {{ old('tipo_documento', $cliente->tipo_documento) == 'TI' ? 'selected' : '' }}>
                            Tarjeta de Identidad (TI)
                        </option>

                        <option value="NIT" {{ old('tipo_documento', $cliente->tipo_documento) == 'NIT' ? 'selected' : '' }}>
                            NIT
                        </option>

                        <option value="PASAPORTE" {{ old('tipo_documento', $cliente->tipo_documento) == 'PASAPORTE' ? 'selected' : '' }}>
                            Pasaporte
                        </option>
                    </select>

                    @error('tipo_documento')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="numero_documento"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Número de documento
                    </label>

                    <input
                        type="text"
                        name="numero_documento"
                        id="numero_documento"
                        value="{{ old('numero_documento', $cliente->numero_documento) }}"
                        maxlength="50"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        placeholder="Ingrese el número"
                    >

                    @error('numero_documento')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Teléfono y correo --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label
                        for="telefono"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        value="{{ old('telefono', $cliente->telefono) }}"
                        maxlength="20"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        placeholder="Ingrese el teléfono"
                    >

                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="correo_electronico"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        name="correo_electronico"
                        id="correo_electronico"
                        value="{{ old('correo_electronico', $cliente->correo_electronico) }}"
                        maxlength="150"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        placeholder="ejemplo@correo.com"
                    >

                    @error('correo_electronico')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Dirección --}}
            <div>
                <label
                    for="direccion"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Dirección
                </label>

                <input
                    type="text"
                    name="direccion"
                    id="direccion"
                    value="{{ old('direccion', $cliente->direccion) }}"
                    maxlength="255"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                    placeholder="Ingrese la dirección"
                >

                @error('direccion')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('cliente.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700"
                >
                    Actualizar cliente
                </button>

            </div>

        </form>

    </div>

</div>

@endsection