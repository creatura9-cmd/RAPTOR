@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

<div class="bg-white shadow-lg rounded-lg p-6">

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold text-gray-700">
        Listado de Repuestos
    </h2>

    <a href="{{ route('repuesto.create') }}"
       class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition">

        Nuevo Repuesto

    </a>

</div>

@if(session('update'))

    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">

        {{ session('update') }}

    </div>

@endif

@if(session('destroy'))

    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

        {{ session('destroy') }}

    </div>

@endif

<table class="min-w-full border border-gray-300">

    <thead>

        <tr class="bg-gray-100">

            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Nombre</th>
            <th class="border px-4 py-2">Marca</th>
            <th class="border px-4 py-2">Categoría</th>
            <th class="border px-4 py-2">Precio</th>
            <th class="border px-4 py-2">Acciones</th>

        </tr>

    </thead>

    <tbody>

        @forelse ($repuestos as $repuesto)

            <tr>

                <td class="border px-4 py-2">
                    {{ $repuesto->id }}
                </td>

                <td class="border px-4 py-2">
                    {{ $repuesto->nombre }}
                </td>

                <td class="border px-4 py-2">
                    {{ $repuesto->marca ?? 'Sin marca' }}
                </td>

                <td class="border px-4 py-2">
                    {{ ucfirst($repuesto->categoria) }}
                </td>

                <td class="border px-4 py-2">
                    ${{ number_format($repuesto->precio, 2) }}
                </td>

                <td class="border px-4 py-2">

                    <div class="flex gap-2">

                        <a href="{{ route('repuesto.edit', $repuesto->id) }}"
                           class="text-blue-600 hover:text-blue-800"
                           title="Editar">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-1.5L19 4.5a2.121 2.121 0 013 3L11.5 18H8v-3.5L17.5 5"/>

                            </svg>

                        </a>

                        <form action="{{ route('repuesto.destroy', $repuesto->id) }}"
                              method="POST"
                              onsubmit="return confirm('¿Está seguro de eliminar este repuesto?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="text-red-600 hover:text-red-800"
                                    title="Eliminar">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>

                                </svg>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6"
                    class="border px-4 py-6 text-center text-gray-500">

                    No hay repuestos registrados.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

</div>

</div>

@endsection
