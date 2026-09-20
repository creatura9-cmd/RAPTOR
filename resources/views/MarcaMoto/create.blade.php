@extends('layouts.app')

@section('title', 'Nueva Marca de Moto')

@section('content')

<div class="max-w-xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <h1 class="text-2xl font-bold text-slate-800 mb-6">
            Nueva Marca de Moto
        </h1>

        <form action="{{ route('marca_moto.store') }}" method="POST">

            @csrf

            <div class="mb-5">

                <label
                    for="nombre_marca"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Nombre de la marca
                </label>

                <input
                    type="text"
                    name="nombre_marca"
                    id="nombre_marca"
                    value="{{ old('nombre_marca') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                @error('nombre_marca')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700"
                >
                    Guardar
                </button>

                <a
                    href="{{ route('marca_moto.index') }}"
                    class="rounded-lg bg-slate-500 px-5 py-2 text-white hover:bg-slate-600"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection