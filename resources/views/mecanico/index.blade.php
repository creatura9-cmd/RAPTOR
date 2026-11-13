 @extends('layouts.app')

@section('title', 'Mecánicos')
@section('page-title', 'Gestión de mecánicos')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-[#315A72]">
                Gestión de mecánicos
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra la información personal, de contacto y especialidad de los mecánicos.
            </p>
        </div>

        <a
            href="{{ route('mecanico.create') }}"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]"
        >

          +  Nuevo mecánico
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de mecánicos --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-[#315A72]">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Mecánico</th>
                        <th class="px-5 py-4 font-semibold">Documento</th>
                        <th class="px-5 py-4 font-semibold">Teléfono</th>
                        <th class="px-5 py-4 font-semibold">Correo electrónico</th>
                        <th class="px-5 py-4 font-semibold">Especialidad</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e5edf1]">

                    @forelse ($mecanicos as $mecanico)

                        <tr class="transition duration-200 hover:bg-[#eaf2f6]">

                            {{-- Mecánico --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        <svg viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 15H6a4 4 0 0 0-4 4v2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.305 16.53.923-.382" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15.228 13.852-.923-.383" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.852 12.228-.383-.923" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.852 17.772-.383.924" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.148 12.228.383-.923" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.53 18.696-.382-.924" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.772 13.852.924-.383" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.772 16.148.924.383" />
                                            <circle cx="18" cy="15" r="3" stroke-width="2" />
                                            <circle cx="9" cy="7" r="4" stroke-width="2" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $mecanico->nombre }} {{ $mecanico->apellido }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            MECÁNICO #{{ $mecanico->id_mecanico }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Documento --}}
                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-700">
                                    {{ $mecanico->tipo_documento }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $mecanico->numero_documento }}
                                </p>
                            </td>

                            {{-- Teléfono --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $mecanico->telefono ?: 'No registrado' }}
                            </td>

                            {{-- Correo --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $mecanico->correo_electronico ?: 'No registrado' }}
                            </td>

                            {{-- Especialidad --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $mecanico->especialidad ?: 'No registrada' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('mecanico.edit', $mecanico->id_mecanico) }}"
                                        title="Editar mecánico"
                                        aria-label="Editar mecánico"
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
                                        action="{{ route('mecanico.destroy', $mecanico->id_mecanico) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de eliminar este mecánico?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar mecánico"
                                            aria-label="Eliminar mecánico"
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
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                                No hay mecánicos registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection