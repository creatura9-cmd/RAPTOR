@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6">

    {{-- Encabezado --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#eaf1f4] text-[#315A72]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-6 h-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M2.25 8.25h19.5M2.25 9.75h19.5m-18 3h4.5m-4.5 3h7.5M3.75 5.25h16.5A1.5 1.5 0 0121.75 6.75v10.5a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6.75a1.5 1.5 0 011.5-1.5z"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Registrar pago
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Registra un pago o abono asociado a una recepción.
                </p>
            </div>
        </div>
    </div>

    {{-- Errores generales --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 flex-shrink-0 mt-0.5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v3.75m0 3h.008v.008H12v-.008zM10.29 3.86l-8.1 14.02A1.5 1.5 0 003.49 20.1h17.02a1.5 1.5 0 001.3-2.22l-8.1-14.02a1.5 1.5 0 00-2.6 0z"/>
                </svg>

                <div>
                    <p class="font-semibold text-sm mb-1">
                        No fue posible registrar el pago.
                    </p>

                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Formulario --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <form action="{{ route('pago.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Recepción --}}
                <div class="md:col-span-2">
                    <label for="id_preorden"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Recepción
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="id_preorden"
                        id="id_preorden"
                        class="w-full rounded-xl border-gray-300 text-sm text-gray-700
                               focus:border-[#315A72] focus:ring-[#315A72]
                               disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                        required
                        {{ $idPreorden ? 'disabled' : '' }}
                    >
                        <option value="">
                            Seleccione una recepción
                        </option>

                        @foreach ($preordenes as $preorden)

                            @php
                                $inventario = $preorden->diagnostico?->inventario;
                                $moto = $inventario?->moto;
                                $cliente = $moto?->cliente;

                                $folio = 'RECEPCIÓN # ' . str_pad(
                                    (string) ($inventario?->recepcion ?? ''),
                                    3,
                                    '0',
                                    STR_PAD_LEFT
                                );

                                $nombreCliente = trim(
                                    ($cliente?->nombre ?? 'Sin cliente') . ' ' .
                                    ($cliente?->apellido ?? '')
                                );

                                $saldo = (float) $preorden->total -
                                    (float) $preorden->pagos
                                        ->where('estado', 'confirmado')
                                        ->sum('valor');
                            @endphp

                            <option
                                value="{{ $preorden->id }}"
                                {{ old('id_preorden', $idPreorden) == $preorden->id ? 'selected' : '' }}
                            >
                                {{ $folio }} -
                                {{ $moto?->placa ?? 'Sin placa' }} -
                                {{ $nombreCliente }} -
                                Saldo: ${{ number_format($saldo, 2, ',', '.') }}
                            </option>

                        @endforeach
                    </select>

                    {{-- Mantener la preorden cuando se ingresa desde una OT --}}
                    @if ($idPreorden)
                        <input
                            type="hidden"
                            name="id_preorden"
                            value="{{ $idPreorden }}"
                        >
                    @endif

                    {{-- OT opcional --}}
                    @if ($idOrdenTrabajo)
                        <input
                            type="hidden"
                            name="id_ordentrabajo"
                            value="{{ $idOrdenTrabajo }}"
                        >
                    @endif

                    <p class="mt-2 text-xs text-gray-500">
                        @if ($idPreorden)
                            La recepción está seleccionada y no se puede modificar.
                        @else
                            Selecciona la recepción correspondiente al pago que deseas registrar.
                        @endif
                    </p>

                    @error('id_preorden')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tipo de pago --}}
                <div>
                    <label for="tipo_pago"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipo de pago
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="tipo_pago"
                        id="tipo_pago"
                        class="w-full rounded-xl border-gray-300 text-sm
                               focus:border-[#315A72] focus:ring-[#315A72]"
                        required
                    >
                        <option value="">Seleccione un tipo</option>

                        <option value="efectivo"
                            {{ old('tipo_pago') == 'efectivo' ? 'selected' : '' }}>
                            Efectivo
                        </option>

                        <option value="transferencia"
                            {{ old('tipo_pago') == 'transferencia' ? 'selected' : '' }}>
                            Transferencia
                        </option>

                        <option value="tarjeta"
                            {{ old('tipo_pago') == 'tarjeta' ? 'selected' : '' }}>
                            Tarjeta
                        </option>
                    </select>

                    @error('tipo_pago')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Fecha de pago --}}
                <div>
                    <label for="fecha_pago"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Fecha de pago
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="fecha_pago"
                        id="fecha_pago"
                        value="{{ old('fecha_pago', date('Y-m-d')) }}"
                        class="w-full rounded-xl border-gray-300 text-sm
                               focus:border-[#315A72] focus:ring-[#315A72]"
                        required
                    >

                    @error('fecha_pago')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Valor --}}
                <div>
                    <label for="valor"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Valor del pago
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">
                            $
                        </span>

                        <input
                            type="number"
                            name="valor"
                            id="valor"
                            value="{{ old('valor') }}"
                            min="0.01"
                            step="0.01"
                            inputmode="decimal"
                            class="w-full pl-8 rounded-xl border-gray-300 text-sm
                                   focus:border-[#315A72] focus:ring-[#315A72]"
                            placeholder="Ej. 554484.96"
                            required
                        >
                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Ingresa el valor sin puntos de miles. Utiliza punto para los decimales.
                    </p>

                    @error('valor')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Estado del pago
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="w-full rounded-xl border-gray-300 text-sm
                               focus:border-[#315A72] focus:ring-[#315A72]"
                        required
                    >
                        <option value="pendiente"
                            {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>
                            Pendiente
                        </option>

                        <option value="confirmado"
                            {{ old('estado') == 'confirmado' ? 'selected' : '' }}>
                            Confirmado
                        </option>

                        <option value="rechazado"
                            {{ old('estado') == 'rechazado' ? 'selected' : '' }}>
                            Rechazado
                        </option>
                    </select>

                    @error('estado')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Observaciones --}}
                <div class="md:col-span-2">
                    <label for="observaciones"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        id="observaciones"
                        rows="4"
                        class="w-full rounded-xl border-gray-300 text-sm
                               focus:border-[#315A72] focus:ring-[#315A72]
                               resize-y"
                        placeholder="Ingresa alguna observación relacionada con el pago..."
                    >{{ old('observaciones') }}</textarea>

                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Botones --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 pt-5 border-t border-gray-100">

                <a
                    href="{{ route('pago.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5
                           border border-gray-300 text-gray-600 text-sm font-medium
                           rounded-xl hover:bg-gray-50 transition"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5
                           bg-[#315A72] hover:bg-[#26495d] text-white text-sm
                           font-semibold rounded-xl shadow-sm transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>

                    Registrar pago
                </button>

            </div>

        </form>
    </div>

</div>

@endsection
