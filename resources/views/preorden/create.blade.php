@extends('layouts.app')

@section('title', 'Nueva preorden')
@section('page-title', 'Registrar preorden')

@section('content')

<div class="mx-auto max-w-5xl space-y-5">

    {{-- Encabezado --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            Registrar preorden
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Administra el presupuesto y los repuestos asociados a esta recepción.
        </p>
    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-700">
                No fue posible guardar la información.
            </p>

            <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('preorden.store') }}"
        method="POST"
        id="form-preorden"
        class="space-y-5">

        @csrf

        {{-- INFORMACIÓN DE LA RECEPCIÓN --}}
        <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-800">
                    Información de la recepción
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Selecciona la recepción para registrar su presupuesto.
                </p>
            </div>

            <div>
                <label for="id_diagnostico"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Recepción finalizada
                </label>

                <select
                    name="id_diagnostico"
                    id="id_diagnostico"
                    required
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                    <option value="">
                        Selecciona una recepción
                    </option>

                    @foreach ($diagnosticos as $diagnostico)
                        <option
                            value="{{ $diagnostico->id }}"
                            @selected(old('id_diagnostico') == $diagnostico->id)>

                            RECEPCIÓN #{{ str_pad($diagnostico->inventario?->recepcion ?? '', 3, '0', STR_PAD_LEFT) }}

                            @if ($diagnostico->inventario?->moto)
                                — {{ $diagnostico->inventario->moto->placa }}
                                — {{ $diagnostico->inventario->moto->modelo }}
                            @endif

                        </option>
                    @endforeach

                </select>

                @if ($diagnosticos->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        No hay recepciones con diagnóstico finalizado disponibles para registrar una preorden.
                    </p>
                @endif

                @error('id_diagnostico')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- PRESUPUESTO --}}
        <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-800">
                    Presupuesto
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Registra la descripción del servicio y el valor de la mano de obra.
                </p>
            </div>

            {{-- Descripción --}}
            <div class="mb-5">
                <label for="descripcion"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Descripción del presupuesto
                </label>

                <textarea
                    name="descripcion"
                    id="descripcion"
                    rows="4"
                    placeholder="Describe los trabajos y servicios presupuestados..."
                    class="w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">{{ old('descripcion') }}</textarea>

                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Mano de obra --}}
            <div>
                <label for="mano_obra"
                    class="mb-2 block text-sm font-semibold text-slate-700">
                    Mano de obra
                </label>

                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                        $
                    </span>

                    <input
                        type="number"
                        name="mano_obra"
                        id="mano_obra"
                        min="0"
                        step="0.01"
                        required
                        value="{{ old('mano_obra', '0.00') }}"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-[#cbd9e0] py-2.5 pl-8 pr-3 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">
                </div>

                @error('mano_obra')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- REPUESTOS --}}
        <div class="rounded-xl border border-[#dce8ed] bg-white p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-800">
                    Repuestos
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Agrega todos los repuestos necesarios para esta recepción.
                </p>
            </div>

            @if ($repuestos->isEmpty())

                <p class="rounded-lg border border-dashed border-[#bfd2dc] bg-[#f8fbfc] p-4 text-sm text-slate-600">
                    No hay repuestos registrados. Registra primero los repuestos en el módulo correspondiente.
                </p>

            @else

                {{-- Contenedor de filas --}}
                <div id="contenedor-repuestos" class="space-y-4">

                    @php
                        $repuestosAnteriores = old('repuestos', [
                            ['id_repuesto' => '', 'cantidad' => 1]
                        ]);
                    @endphp

                    @foreach ($repuestosAnteriores as $indice => $linea)

                        <div class="fila-repuesto rounded-lg border border-slate-200 bg-[#fbfdfe] p-4">

                            <div class="mb-4 flex items-center justify-between gap-3">

                                <p class="text-sm font-semibold text-slate-700">
                                    Repuesto <span class="numero-repuesto">{{ $indice + 1 }}</span>
                                </p>

                                <button
                                    type="button"
                                    class="eliminar-repuesto rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    Eliminar
                                </button>

                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                {{-- Selección --}}
                                <div class="sm:col-span-2">

                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Repuesto
                                    </label>

                                    <select
                                        name="repuestos[{{ $indice }}][id_repuesto]"
                                        required
                                        class="select-repuesto w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                                        <option value="" data-precio="0">
                                            Selecciona un repuesto
                                        </option>

                                        @foreach ($repuestos as $repuesto)
                                            <option
                                                value="{{ $repuesto->id }}"
                                                data-precio="{{ $repuesto->precio }}"
                                                @selected(($linea['id_repuesto'] ?? '') == $repuesto->id)>

                                                {{ $repuesto->nombre }}
                                                — ${{ number_format($repuesto->precio, 2, ',', '.') }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                                {{-- Cantidad --}}
                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Cantidad
                                    </label>

                                    <input
                                        type="number"
                                        name="repuestos[{{ $indice }}][cantidad]"
                                        min="1"
                                        step="1"
                                        required
                                        value="{{ $linea['cantidad'] ?? 1 }}"
                                        class="cantidad-repuesto w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                                </div>

                                {{-- Valores --}}
                                <div class="rounded-lg bg-[#f0f5f7] p-3">

                                    <div class="flex flex-wrap items-center justify-between gap-2">

                                        <div>
                                            <p class="text-xs font-medium text-slate-500">
                                                Precio unitario
                                            </p>

                                            <p class="precio-visible mt-1 text-sm font-bold text-[#315A72]">
                                                $0,00
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium text-slate-500">
                                                Subtotal
                                            </p>

                                            <p class="subtotal-visible mt-1 text-sm font-bold text-[#315A72]">
                                                $0,00
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- Agregar otra fila --}}
                <div class="mt-5 flex justify-end border-t border-[#e5edf1] pt-5">

                    <button
                        type="button"
                        id="agregar-repuesto"
                        class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">

                        + Agregar otro repuesto

                    </button>

                </div>

            @endif

        </div>

        {{-- RESUMEN ECONÓMICO --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            {{-- Mano de obra --}}
            <div class="rounded-lg border border-[#dce8ed] bg-white px-4 py-3 shadow-sm">

                <p class="text-xs font-medium text-slate-500">
                    Mano de obra
                </p>

                <p id="resumen-mano-obra" class="mt-1 text-base font-semibold text-slate-800">
                    $0,00
                </p>

            </div>

            {{-- Total repuestos --}}
            <div class="rounded-lg border border-[#dce8ed] bg-white px-4 py-3 shadow-sm">

                <p class="text-xs font-medium text-slate-500">
                    Total repuestos
                </p>

                <p id="resumen-repuestos" class="mt-1 text-base font-semibold text-slate-800">
                    $0,00
                </p>

            </div>

            {{-- Total general --}}
            <div class="rounded-lg border border-[#dce8ed] bg-[#f0f5f7] px-4 py-3 shadow-sm">

                <p class="text-xs font-medium text-slate-500">
                    Total general
                </p>

                <p id="resumen-total" class="mt-1 text-base font-bold text-[#315A72]">
                    $0,00
                </p>

            </div>

        </div>

        {{-- Estado inicial --}}
        <input type="hidden" name="estado" value="pendiente">

        {{-- ACCIONES --}}
        <div class="flex flex-wrap justify-end gap-3 border-t border-[#e5edf1] pt-5">

            <a
                href="{{ route('preorden.index') }}"
                class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                Cancelar
            </a>

            <button
                type="submit"
                class="rounded-lg bg-[#315A72] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294c61]">

                Guardar preorden

            </button>

        </div>

    </form>

</div>

{{-- Cálculos visuales del presupuesto --}}
@if (!$repuestos->isEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const contenedor = document.getElementById('contenedor-repuestos');
        const botonAgregar = document.getElementById('agregar-repuesto');
        const manoObraInput = document.getElementById('mano_obra');

        const resumenManoObra = document.getElementById('resumen-mano-obra');
        const resumenRepuestos = document.getElementById('resumen-repuestos');
        const resumenTotal = document.getElementById('resumen-total');

        const formatoMoneda = new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        let siguienteIndice = {{ count($repuestosAnteriores) }};

        // Actualizar la numeración de las filas.
        function actualizarNumeracion() {

            contenedor.querySelectorAll('.fila-repuesto').forEach(function (fila, indice) {
                fila.querySelector('.numero-repuesto').textContent = indice + 1;
            });

        }

        // Calcular una fila.
        function calcularFila(fila) {

            const select = fila.querySelector('.select-repuesto');
            const cantidadInput = fila.querySelector('.cantidad-repuesto');

            const precioVisible = fila.querySelector('.precio-visible');
            const subtotalVisible = fila.querySelector('.subtotal-visible');

            const opcion = select.options[select.selectedIndex];

            const precio = Number(opcion?.dataset.precio || 0);
            const cantidad = Math.max(1, Number(cantidadInput.value || 1));

            const subtotal = precio * cantidad;

            precioVisible.textContent = formatoMoneda.format(precio);
            subtotalVisible.textContent = formatoMoneda.format(subtotal);

            return subtotal;
        }

        // Calcular todo el presupuesto.
        function calcularResumen() {

            let totalRepuestos = 0;

            contenedor.querySelectorAll('.fila-repuesto').forEach(function (fila) {
                totalRepuestos += calcularFila(fila);
            });

            const manoObra = Math.max(0, Number(manoObraInput.value || 0));
            const totalGeneral = manoObra + totalRepuestos;

            resumenManoObra.textContent = formatoMoneda.format(manoObra);
            resumenRepuestos.textContent = formatoMoneda.format(totalRepuestos);
            resumenTotal.textContent = formatoMoneda.format(totalGeneral);

        }

        // Crear una nueva fila de repuesto.
        function crearFilaRepuesto(indice) {

            const fila = document.createElement('div');

            fila.className = 'fila-repuesto rounded-lg border border-slate-200 bg-[#fbfdfe] p-4';

            fila.innerHTML = `
                <div class="mb-4 flex items-center justify-between gap-3">

                    <p class="text-sm font-semibold text-slate-700">
                        Repuesto <span class="numero-repuesto"></span>
                    </p>

                    <button
                        type="button"
                        class="eliminar-repuesto rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                        Eliminar
                    </button>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="sm:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Repuesto
                        </label>

                        <select
                            name="repuestos[${indice}][id_repuesto]"
                            required
                            class="select-repuesto w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">
                        </select>

                    </div>

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Cantidad
                        </label>

                        <input
                            type="number"
                            name="repuestos[${indice}][cantidad]"
                            min="1"
                            step="1"
                            required
                            value="1"
                            class="cantidad-repuesto w-full rounded-lg border border-[#cbd9e0] px-3 py-2.5 text-sm focus:border-[#315A72] focus:outline-none focus:ring-2 focus:ring-[#315A72]/20">

                    </div>

                    <div class="rounded-lg bg-[#f0f5f7] p-3">

                        <div class="flex flex-wrap items-center justify-between gap-2">

                            <div>
                                <p class="text-xs font-medium text-slate-500">
                                    Precio unitario
                                </p>

                                <p class="precio-visible mt-1 text-sm font-bold text-[#315A72]">
                                    $0,00
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium text-slate-500">
                                    Subtotal
                                </p>

                                <p class="subtotal-visible mt-1 text-sm font-bold text-[#315A72]">
                                    $0,00
                                </p>
                            </div>

                        </div>

                    </div>

                </div>
            `;

            // Copiar las opciones del selector original.
            const selectorOriginal = contenedor.querySelector('.select-repuesto');
            const nuevoSelector = fila.querySelector('.select-repuesto');

            nuevoSelector.innerHTML = selectorOriginal.innerHTML;
            nuevoSelector.selectedIndex = 0;

            return fila;
        }

        // Agregar repuesto.
        botonAgregar.addEventListener('click', function () {

            const nuevaFila = crearFilaRepuesto(siguienteIndice);

            contenedor.appendChild(nuevaFila);

            siguienteIndice++;

            actualizarNumeracion();
            calcularResumen();

        });

        // Eliminar repuesto.
        contenedor.addEventListener('click', function (evento) {

            const boton = evento.target.closest('.eliminar-repuesto');

            if (!boton) {
                return;
            }

            const filas = contenedor.querySelectorAll('.fila-repuesto');

            if (filas.length <= 1) {
                const fila = boton.closest('.fila-repuesto');

                fila.querySelector('.select-repuesto').value = '';
                fila.querySelector('.cantidad-repuesto').value = 1;

            } else {
                boton.closest('.fila-repuesto').remove();
            }

            actualizarNumeracion();
            calcularResumen();

        });

        // Cambios en selección y cantidad.
        contenedor.addEventListener('change', function (evento) {

            if (
                evento.target.classList.contains('select-repuesto') ||
                evento.target.classList.contains('cantidad-repuesto')
            ) {
                calcularResumen();
            }

        });

        contenedor.addEventListener('input', function (evento) {

            if (evento.target.classList.contains('cantidad-repuesto')) {
                calcularResumen();
            }

        });

        // Actualizar mano de obra.
        manoObraInput.addEventListener('input', calcularResumen);

        // Cálculo inicial.
        actualizarNumeracion();
        calcularResumen();

    });
</script>
@endif

@endsection