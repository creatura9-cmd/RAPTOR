@extends('layouts.app')

@section('title', 'Editar Mecánico')

@section('content')

<div class="max-w-xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <h1 class="text-2xl font-bold text-slate-800 mb-6">
            Editar Mecánico
        </h1>

        <form
            action="{{ route('mecanico.update', $mecanico->id_mecanico) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="mb-5">

                <label
                    for="nombre"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    value="{{ old('nombre', $mecanico->nombre) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

            </div>

            <div class="mb-5">

                <label
                    for="apellido"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Apellido
                </label>

                <input
                    type="text"
                    name="apellido"
                    id="apellido"
                    value="{{ old('apellido', $mecanico->apellido) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

            </div>

            <div class="mb-5">

                <label
                    for="tipo_documento"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Tipo de documento
                </label>

                <select
                    name="tipo_documento"
                    id="tipo_documento"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                    <option value="">Seleccione</option>

                    <option value="CC"
                        {{ old('tipo_documento', $mecanico->tipo_documento) == 'CC' ? 'selected' : '' }}>
                        CC
                    </option>

                    <option value="CE"
                        {{ old('tipo_documento', $mecanico->tipo_documento) == 'CE' ? 'selected' : '' }}>
                        CE
                    </option>

                    <option value="TI"
                        {{ old('tipo_documento', $mecanico->tipo_documento) == 'TI' ? 'selected' : '' }}>
                        TI
                    </option>

                    <option value="PAS"
                        {{ old('tipo_documento', $mecanico->tipo_documento) == 'PAS' ? 'selected' : '' }}>
                        PAS
                    </option>

                </select>

            </div>

            <div class="mb-5">

                <label
                    for="numero_documento"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Número de documento
                </label>

                <input
                    type="text"
                    name="numero_documento"
                    id="numero_documento"
                    value="{{ old('numero_documento', $mecanico->numero_documento) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

            </div>

            <div class="mb-5">

                <label
                    for="telefono"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Teléfono
                </label>

                <input
                    type="text"
                    name="telefono"
                    id="telefono"
                    value="{{ old('telefono', $mecanico->telefono) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

            </div>

            <div class="mb-5">

                <label
                    for="correo_electronico"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Correo electrónico
                </label>

                <input
                    type="email"
                    name="correo_electronico"
                    id="correo_electronico"
                    value="{{ old('correo_electronico', $mecanico->correo_electronico) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

            </div>

            <div class="mb-5">

                <label
                    for="especialidad"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Especialidad
                </label>

                <input
                    type="text"
                    name="especialidad"
                    id="especialidad"
                    value="{{ old('especialidad', $mecanico->especialidad) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700"
                >
                    Actualizar
                </button>

                <a
                    href="{{ route('mecanico.index') }}"
                    class="rounded-lg bg-slate-500 px-5 py-2 text-white hover:bg-slate-600"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection