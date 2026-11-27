@extends('historial.layouts.pdf')

@section('titulo', 'INFORME DE SERVICIO')

@section('contenido')

{{-- FOLIO DE RECEPCIÓN --}}
<div class="folio">
    {{ $folioRecepcion }}
</div>

{{-- 1. INFORMACIÓN DEL CLIENTE --}}
<div class="seccion">

    <div class="seccion-titulo">
        1. INFORMACIÓN DEL CLIENTE
    </div>

    <table class="datos">
        <tr>
            <td class="etiqueta">Nombre:</td>
            <td class="valor">
                {{ $nombreCliente ?: 'No disponible' }}
            </td>

            <td class="etiqueta">Documento:</td>
            <td class="valor">
                {{ $cliente?->numero_documento ?? 'No disponible' }}
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Teléfono:</td>
            <td>
                {{ $cliente?->telefono ?? 'No disponible' }}
            </td>

            <td class="etiqueta">Correo:</td>
            <td>
                {{ $cliente?->correo_electronico ?: 'No disponible' }}
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Dirección:</td>
            <td colspan="3">
                {{ $cliente?->direccion ?? 'No disponible' }}
            </td>
        </tr>
    </table>

</div>

{{-- 2. INFORMACIÓN DE LA MOTOCICLETA --}}
<div class="seccion">

    <div class="seccion-titulo">
        2. INFORMACIÓN DE LA MOTOCICLETA
    </div>

    <table class="datos">
        <tr>
            <td class="etiqueta">Marca:</td>
            <td class="valor">
                {{ $moto?->marca?->nombre_marca ?? 'No disponible' }}
            </td>

            <td class="etiqueta">Modelo:</td>
            <td class="valor">
                {{ $moto?->modelo ?? 'No disponible' }}
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Placa:</td>
            <td>
                {{ $moto?->placa ?? 'No disponible' }}
            </td>

            <td class="etiqueta">Condición:</td>
            <td>
                {{ $inventario?->condicion_general ?? 'No disponible' }}
            </td>
        </tr>
    </table>

</div>

{{-- 3. RECEPCIÓN --}}
<div class="seccion">

    <div class="seccion-titulo">
        3. RECEPCIÓN DE LA MOTOCICLETA
    </div>

    <table class="datos">
        <tr>
            <td class="etiqueta">Fecha de recepción:</td>
            <td class="valor">
                {{ $inventario?->fecha_registro ?? 'No disponible' }}
            </td>

            <td class="etiqueta">Estado:</td>
            <td class="valor">
                {{ $inventario?->estado_inventario ?? 'No disponible' }}
            </td>
        </tr>
    </table>

    <p><strong>Descripción de recepción:</strong></p>

    <div class="observaciones">
        {{ $inventario?->descripcion ?? 'Sin descripción registrada.' }}
    </div>

</div>

{{-- 4. DIAGNÓSTICO TÉCNICO --}}
<div class="seccion">

    <div class="seccion-titulo">
        4. DIAGNÓSTICO TÉCNICO
    </div>

    <table class="datos">
        <tr>
            <td class="etiqueta">Fecha:</td>
            <td class="valor">
                {{ $diagnostico?->fecha_diagnostico ?? 'No disponible' }}
            </td>

            <td class="etiqueta">Estado:</td>
            <td class="valor">
                {{ $diagnostico?->estado ?? 'No disponible' }}
            </td>
        </tr>
    </table>

    <p><strong>Descripción del diagnóstico:</strong></p>

    <div class="observaciones">
        {{ $diagnostico?->descripcion ?? 'Sin diagnóstico registrado.' }}
    </div>

</div>

{{-- 5. TRABAJOS REALIZADOS --}}
<div class="seccion">

    <div class="seccion-titulo">
        5. TRABAJOS REALIZADOS
    </div>

    <p><strong>Descripción de los trabajos:</strong></p>

    <div class="observaciones">
        {{ $preorden?->descripcion ?? 'Sin descripción de trabajos registrada.' }}
    </div>

    <table class="datos">
        <tr>
            <td class="etiqueta">Mano de obra:</td>
            <td class="valor">
                ${{ number_format((float) ($preorden?->mano_obra ?? 0), 2, ',', '.') }}
            </td>

            <td class="etiqueta">Mecánico:</td>
            <td class="valor">
                {{ $nombreMecanico ?: 'No disponible' }}
            </td>
        </tr>
    </table>

</div>

{{-- 6. REPUESTOS UTILIZADOS --}}
<div class="seccion">

    <div class="seccion-titulo">
        6. REPUESTOS UTILIZADOS
    </div>

    <table class="tabla">

        <thead>
            <tr>
                <th>Repuesto</th>
                <th class="centro">Cantidad</th>
                <th class="derecha">Precio unitario</th>
                <th class="derecha">Subtotal</th>
            </tr>
        </thead>

        <tbody>

            @forelse($preorden?->repuestos ?? [] as $detalle)

                <tr>
                    <td>
                        {{ $detalle->repuesto?->nombre ?? 'No disponible' }}
                    </td>

                    <td class="centro">
                        {{ $detalle->cantidad }}
                    </td>

                    <td class="derecha">
                        ${{ number_format((float) $detalle->precio_unitario, 2, ',', '.') }}
                    </td>

                    <td class="derecha">
                        ${{ number_format((float) $detalle->subtotal, 2, ',', '.') }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="vacio centro">
                        No se registraron repuestos.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- 7. REGISTRO DE PAGOS --}}
<div class="seccion">

    <div class="seccion-titulo">
        7. REGISTRO DE PAGOS
    </div>

    <table class="tabla">

        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo de pago</th>
                <th>Estado</th>
                <th class="derecha">Valor</th>
            </tr>
        </thead>

        <tbody>

            @forelse($preorden?->pagos ?? [] as $pago)

                <tr>
                    <td>
                        {{ $pago->fecha_pago ?? 'No disponible' }}
                    </td>

                    <td>
                        {{ $pago->tipo_pago ?? 'No disponible' }}
                    </td>

                    <td>
                        {{ ucfirst($pago->estado ?? 'No disponible') }}
                    </td>

                    <td class="derecha">
                        ${{ number_format((float) $pago->valor, 2, ',', '.') }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="vacio centro">
                        No se registraron pagos.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <table class="datos" style="margin-top: 10px;">

        <tr>
            <td class="etiqueta">Total del servicio:</td>
            <td class="derecha">
                <strong>
                    ${{ number_format($totalServicio, 2, ',', '.') }}
                </strong>
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Total pagado:</td>
            <td class="derecha">
                ${{ number_format($totalPagado, 2, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td class="etiqueta">Saldo pendiente:</td>
            <td class="derecha">
                <strong>
                    ${{ number_format($saldoPendiente, 2, ',', '.') }}
                </strong>
            </td>
        </tr>

    </table>

</div>

{{-- 8. ENTREGA --}}
<div class="seccion">

    <div class="seccion-titulo">
        8. ENTREGA DE LA MOTOCICLETA
    </div>

    <table class="datos">
        <tr>
            <td class="etiqueta">Fecha de entrega:</td>
            <td class="valor">
                {{ $entrega?->fecha_entrega ?? 'No disponible' }}
            </td>

            <td class="etiqueta">Estado del servicio:</td>
            <td class="valor">
                {{ ucfirst(str_replace('_', ' ', $servicio->estado ?? '')) }}
            </td>
        </tr>
    </table>

    <p><strong>Observaciones de entrega:</strong></p>

    <div class="observaciones">
        {{ $entrega?->observaciones ?? 'Sin observaciones registradas.' }}
    </div>

</div>

@endsection
