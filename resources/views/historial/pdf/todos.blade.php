@extends('historial.layouts.pdf')

@section('titulo', 'HISTORIAL GENERAL DE SERVICIOS')

@section('contenido')

<div class="folio">
    HISTORIAL GENERAL DE SERVICIOS
</div>

<p>
    <strong>Total de servicios registrados:</strong>
    {{ $servicios->count() }}
</p>

@forelse($servicios as $item)

    @php
        $servicio = $item['servicio'];
        $inventario = $item['inventario'];
        $preorden = $item['preorden'];
        $diagnostico = $item['diagnostico'];
        $moto = $item['moto'];
        $cliente = $item['cliente'];
        $mecanico = $item['mecanico'];
        $entrega = $item['entrega'];

        $folioRecepcion = $item['folioRecepcion'];
        $nombreCliente = $item['nombreCliente'];
        $nombreMecanico = $item['nombreMecanico'];

        $totalServicio = $item['totalServicio'];
        $totalPagado = $item['totalPagado'];
        $saldoPendiente = $item['saldoPendiente'];
    @endphp

    <div class="seccion">

        <div class="seccion-titulo">
            {{ $folioRecepcion }}
        </div>

        <table class="datos">

            <tr>
                <td class="etiqueta">Cliente:</td>
                <td class="valor">
                    {{ $nombreCliente ?: 'No disponible' }}
                </td>

                <td class="etiqueta">Documento:</td>
                <td class="valor">
                    {{ $cliente?->numero_documento ?? 'No disponible' }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Correo:</td>
                <td class="valor" colspan="3">
                    {{ $cliente?->correo_electronico ?: 'No disponible' }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Motocicleta:</td>
                <td class="valor">
                    {{ $moto?->marca?->nombre_marca ?? 'No disponible' }}
                    {{ $moto?->modelo ?? '' }}
                </td>

                <td class="etiqueta">Placa:</td>
                <td class="valor">
                    {{ $moto?->placa ?? 'No disponible' }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Fecha de recepción:</td>
                <td>
                    {{ $inventario?->fecha_registro ?? 'No disponible' }}
                </td>

                <td class="etiqueta">Fecha de entrega:</td>
                <td>
                    {{ $entrega?->fecha_entrega ?? 'No disponible' }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Mecánico:</td>
                <td>
                    {{ $nombreMecanico ?: 'No disponible' }}
                </td>

                <td class="etiqueta">Estado:</td>
                <td>
                    {{ ucfirst($servicio->estado ?? '') }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Diagnóstico:</td>
                <td colspan="3">
                    {{ $diagnostico?->descripcion ?? 'No disponible' }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Trabajos realizados:</td>
                <td colspan="3">
                    {{ $preorden?->descripcion ?? 'No disponible' }}
                </td>
            </tr>

        </table>

        <table class="datos" style="margin-top: 8px;">

            <tr>
                <td class="etiqueta">Mano de obra:</td>
                <td>
                    ${{ number_format((float) ($preorden?->mano_obra ?? 0), 2, ',', '.') }}
                </td>

                <td class="etiqueta">Total servicio:</td>
                <td>
                    ${{ number_format($totalServicio, 2, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td class="etiqueta">Total pagado:</td>
                <td>
                    ${{ number_format($totalPagado, 2, ',', '.') }}
                </td>

                <td class="etiqueta">Saldo pendiente:</td>
                <td>
                    ${{ number_format($saldoPendiente, 2, ',', '.') }}
                </td>
            </tr>

        </table>

        <p><strong>Repuestos utilizados:</strong></p>

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

        <p><strong>Pagos registrados:</strong></p>

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
                            {{ ucfirst($pago->estado ?? '') }}
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

    </div>

@empty

    <div class="vacio centro">
        No existen servicios finalizados y entregados en el historial.
    </div>

@endforelse

@endsection
