@extends('layouts.app')

@section('title', 'Repuestos')
@section('page-title', 'Gestión de repuestos')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-[#315A72]">
                Gestión de repuestos
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra los repuestos, sus marcas, categorías y precios.
            </p>
        </div>

        <a
            href="{{ route('repuesto.create') }}"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]"
        >
            + Nuevo repuesto
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de repuestos --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-[#315A72]">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Repuesto</th>
                        <th class="px-5 py-4 font-semibold">Marca</th>
                        <th class="px-5 py-4 font-semibold">Categoría</th>
                        <th class="px-5 py-4 font-semibold">Precio</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e5edf1]">

                    @forelse ($repuestos as $repuesto)

                        <tr class="transition duration-200 hover:bg-[#eaf2f6]">

                            {{-- Repuesto --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        <svg viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 15h4" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.817 10.995-.971-1.45 1.034-1.232a2 2 0 0 0-2.025-3.238l-1.82.364L9.91 3.885a2 2 0 0 0-3.625.748L6.141 6.55l-1.725.426a2 2 0 0 0-.19 3.756l.657.27" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18.822 10.995 2.26-5.38a1 1 0 0 0-.557-1.318L16.954 2.9a1 1 0 0 0-1.281.533l-.924 2.122" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12.006A1 1 0 0 1 4.994 11H19a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $repuesto->nombre }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            REPUESTO #{{ $repuesto->id }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Marca --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $repuesto->marca ?: 'Sin marca' }}
                            </td>

                            {{-- Categoría --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $repuesto->categoria
                                    ? ucfirst($repuesto->categoria)
                                    : 'Sin categoría' }}
                            </td>

                            {{-- Precio --}}
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-700">
                                ${{ number_format($repuesto->precio, 2) }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('repuesto.edit', $repuesto->id) }}"
                                        title="Editar repuesto"
                                        aria-label="Editar repuesto"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke-width="1.8"
                                             stroke="currentColor"
                                             class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"/>
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form
                                        action="{{ route('repuesto.destroy', $repuesto->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de eliminar este repuesto?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar repuesto"
                                            aria-label="Eliminar repuesto"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="1.8"
                                                 stroke="currentColor"
                                                 class="h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                                No hay repuestos registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
