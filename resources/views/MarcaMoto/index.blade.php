@extends('layouts.app')

@section('title', 'Marcas de motos')
@section('page-title', 'Gestión de marcas de motos')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-[#315A72]">
                Gestión de marcas de motos
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra las marcas de motocicletas registradas en RAPTOR.
            </p>
        </div>

        <a
            href="{{ route('marca_moto.create') }}"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]"
        >
            + Nueva marca
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de marcas --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[500px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-[#315A72]">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Marca</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e5edf1]">

                    @forelse ($marcas as $marca)

                        <tr class="transition duration-200 hover:bg-[#eaf2f6]">

                            {{-- Nombre de la marca --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">

                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-1a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>

                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $marca->nombre_marca }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            MARCA #{{ $marca->id }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('marca_moto.edit', $marca->id) }}"
                                        title="Editar marca"
                                        aria-label="Editar marca"
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
                                        action="{{ route('marca_moto.destroy', $marca->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de eliminar esta marca?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar marca"
                                            aria-label="Eliminar marca"
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
                            <td colspan="2" class="px-5 py-10 text-center text-sm text-slate-500">
                                No hay marcas de motos registradas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
