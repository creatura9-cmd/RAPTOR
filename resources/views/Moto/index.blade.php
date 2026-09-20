```blade
@extends('layouts.app')

@section('title', 'Motos')

@section('content')

<div class="container mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-lg p-6">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-2xl font-bold text-gray-700">
                    Motos
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Gestiona las motocicletas registradas en RAPTOR.
                </p>

            </div>

            <a
                href="{{ route('moto.create') }}"
                class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition"
            >
                + Nueva moto
            </a>

        </div>


        {{-- Mensaje de creación --}}
        @if (session('success'))

            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>

        @endif


        {{-- Mensaje de actualización --}}
        @if (session('update'))

            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                {{ session('update') }}
            </div>

        @endif


        {{-- Mensaje de eliminación --}}
        @if (session('destroy'))

            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('destroy') }}
            </div>

        @endif


        {{-- Tabla --}}
        <table class="min-w-full border border-gray-300">

            <thead class="bg-gray-200">

                <tr>

                    <th class="border px-4 py-2">
                        Cliente
                    </th>

                    <th class="border px-4 py-2">
                        Marca
                    </th>

                    <th class="border px-4 py-2">
                        Modelo
                    </th>

                    <th class="border px-4 py-2">
                        Año
                    </th>

                    <th class="border px-4 py-2">
                        Placa
                    </th>

                    <th class="border px-4 py-2">
                        Acciones
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($motos as $moto)

                    <tr class="text-center hover:bg-gray-50">


                        {{-- Cliente --}}
                        <td class="border px-4 py-2">

                            <div class="font-medium text-gray-700">
                                {{ $moto->cliente->nombre }}
                                {{ $moto->cliente->apellido }}
                            </div>

                        </td>


                        {{-- Marca --}}
                        <td class="border px-4 py-2">

                            <div class="text-sm text-gray-700">
                                {{ $moto->marca->nombre_marca }}
                            </div>

                        </td>


                        {{-- Modelo --}}
                        <td class="border px-4 py-2">

                            <div class="text-sm text-gray-700">
                                {{ $moto->modelo }}
                            </div>

                        </td>


                        {{-- Año --}}
                        <td class="border px-4 py-2">

                            <div class="text-sm text-gray-700">
                                {{ $moto->anio }}
                            </div>

                        </td>


                        {{-- Placa --}}
                        <td class="border px-4 py-2">

                            <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-sm font-semibold text-gray-700">
                                {{ $moto->placa }}
                            </span>

                        </td>


                        {{-- Acciones --}}
                        <td class="border px-4 py-2">

                            <div class="flex items-center justify-center gap-3">

                                {{-- Editar --}}
                                <a
                                    href="{{ route('moto.edit', $moto->id) }}"
                                    class="text-blue-600 hover:text-blue-900 p-1"
                                    title="Editar"
                                >

                                    <svg class="w-5 h-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>

                                    </svg>

                                </a>


                                {{-- Eliminar --}}
                                <form
                                    action="{{ route('moto.destroy', $moto->id) }}"
                                    method="POST"
                                    class="inline"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Está seguro de eliminar esta moto?')"
                                        class="text-red-600 hover:text-red-900 p-1"
                                        title="Eliminar"
                                    >

                                        <svg class="w-5 h-5"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>

                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="border px-4 py-6 text-center text-gray-500"
                        >

                            <div class="text-sm font-medium">
                                No hay motos registradas.
                            </div>

                            <p class="mt-1 text-sm">
                                Comienza registrando la primera moto.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
```

La diferencia principal es **visual**: ahora el recuadro de Motos usa `bg-white shadow-lg rounded-lg p-6`, y la tabla usa `border`, `bg-gray-200` y `hover:bg-gray-50`, igual que `MarcaMoto`. La lógica de tu archivo se mantiene.





