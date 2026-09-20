```blade
@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="container mx-auto mt-10">

    <div class="bg-white shadow-lg rounded-lg p-6">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-2xl font-bold text-gray-700">
                    Clientes
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Gestiona los clientes registrados en RAPTOR.
                </p>

            </div>

            <a
                href="{{ route('cliente.create') }}"
                class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition"
            >
                + Nuevo cliente
            </a>

        </div>


        {{-- Tabla --}}
        <table class="min-w-full border border-gray-300">

            <thead class="bg-gray-200">

                <tr>

                    <th class="border px-4 py-2">
                        Nombre
                    </th>

                    <th class="border px-4 py-2">
                        Documento
                    </th>

                    <th class="border px-4 py-2">
                        Teléfono
                    </th>

                    <th class="border px-4 py-2">
                        Correo
                    </th>

                    <th class="border px-4 py-2">
                        Acciones
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($clientes as $cliente)

                    <tr class="text-center hover:bg-gray-50">

                        <td class="border px-4 py-2">

                            <div class="font-medium text-gray-700">
                                {{ $cliente->nombre }} {{ $cliente->apellido }}
                            </div>

                        </td>


                        <td class="border px-4 py-2">

                            <div class="text-sm text-gray-700">
                                {{ $cliente->tipo_documento }} -
                                {{ $cliente->numero_documento }}
                            </div>

                        </td>


                        <td class="border px-4 py-2 text-sm text-gray-600">

                            {{ $cliente->telefono ?? '—' }}

                        </td>


                        <td class="border px-4 py-2 text-sm text-gray-600">

                            {{ $cliente->correo_electronico ?? '—' }}

                        </td>


                        <td class="border px-4 py-2">

                            <div class="flex items-center justify-center gap-3">

                                {{-- Editar --}}
                                <a
                                    href="{{ route('cliente.edit', $cliente->id) }}"
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
                                    action="{{ route('cliente.destroy', $cliente->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
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
                            colspan="5"
                            class="border px-4 py-6 text-center text-gray-500"
                        >

                            <div class="text-sm font-medium">
                                No hay clientes registrados.
                            </div>

                            <p class="mt-1 text-sm">
                                Comienza registrando el primer cliente.
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

Queda visualmente alineado con **Marcas Moto y Motos**, pero la lógica original de Clientes permanece igual.
