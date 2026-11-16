<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Service\PortalClienteService;
use App\Service\HistorialService;

class PortalClienteController extends Controller
{
    protected $portalClienteService;
    protected $historialService;

    public function __construct(
        PortalClienteService $portalClienteService,
        HistorialService $historialService
    ) {
        $this->portalClienteService = $portalClienteService;
        $this->historialService = $historialService;
    }

    /**
     * Mostrar el portal del cliente.
     */
    public function index()
    {
        $cliente = $this->obtenerClienteAutenticado();

        $datos = $this->portalClienteService
            ->obtenerDatosPortal($cliente);

        return view('portal-cliente.index', $datos);
    }

    /**
     * Aprobar una preorden desde el portal.
     */
    public function aprobar(int $id)
    {
        $cliente = $this->obtenerClienteAutenticado();

        $this->portalClienteService->aprobar($cliente, $id);

        return redirect()
            ->route('cliente.portal')
            ->with('success', 'Has aprobado la preorden correctamente.');
    }

    /**
     * Rechazar una preorden desde el portal.
     */
    public function rechazar(int $id)
    {
        $cliente = $this->obtenerClienteAutenticado();

        $this->portalClienteService->rechazar($cliente, $id);

        return redirect()
            ->route('cliente.portal')
            ->with('success', 'Has rechazado la preorden.');
    }

    /**
     * Descargar el PDF de un servicio del cliente.
     */
    public function descargarServicio(int $id)
    {
        $cliente = $this->obtenerClienteAutenticado();

        $pdf = $this->historialService
            ->generarPdfServicioDelCliente($cliente, $id);

        if (!$pdf) {
            abort(404, 'El servicio no existe en tu historial.');
        }

        return $pdf->stream('RAPTOR-informe-servicio-' . $id . '.pdf');
    }

    /**
     * Descargar el historial PDF de una motocicleta del cliente.
     */
    public function descargarHistorialMoto(int $idMoto)
    {
        $cliente = $this->obtenerClienteAutenticado();

        $pdf = $this->historialService
            ->generarPdfHistorialMotoDelCliente($cliente, $idMoto);

        if (!$pdf) {
            abort(
                404,
                'No tienes servicios entregados registrados para esta motocicleta.'
            );
        }

        return $pdf->stream(
            'RAPTOR-historial-motocicleta-' . $idMoto . '.pdf'
        );
    }

    /**
     * Obtener el cliente asociado al usuario autenticado.
     */
    private function obtenerClienteAutenticado(): Cliente
    {
        $cliente = auth()->user()->cliente;

        abort_unless(
            $cliente,
            403,
            'La cuenta no tiene un cliente asociado.'
        );

        return $cliente;
    }
}
