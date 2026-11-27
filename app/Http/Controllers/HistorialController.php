<?php

namespace App\Http\Controllers;

use App\Service\HistorialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HistorialController extends Controller
{
    protected HistorialService $historialService;

    public function __construct(HistorialService $historialService)
    {
        $this->historialService = $historialService;
    }

    /**
     * Mostrar los clientes y sus motocicletas
     * que tienen servicios finalizados y entregados.
     */
    public function index(): View
    {
        $datos = $this->historialService->obtenerDatosHistorial();

        return view('historial.index', $datos);
    }

    /**
     * Mostrar el PDF del historial completo de un cliente.
     */
    public function descargarHistorialCliente(int $idCliente)
    {
        $pdf = $this->historialService
            ->generarPdfHistorialCliente($idCliente);

        if (!$pdf) {
            abort(404, 'El cliente no tiene servicios registrados en el historial.');
        }

        return $pdf->stream(
            'RAPTOR-historial-cliente-'.$idCliente.'.pdf'
        );
    }

    /**
     * Mostrar el PDF consolidado de una motocicleta.
     */
    public function descargarHistorialMoto(int $idMoto)
    {
        $pdf = $this->historialService->generarPdfHistorialMoto($idMoto);

        if (!$pdf) {
            abort(404, 'La motocicleta no tiene servicios registrados en el historial.');
        }

        return $pdf->stream('RAPTOR-historial-motocicleta-'.$idMoto.'.pdf');
    }

    /**
     * Mostrar el PDF de un servicio específico.
     */
    public function descargar(int $id)
    {
        $pdf = $this->historialService->generarPdfServicio($id);

        if (!$pdf) {
            abort(404, 'El servicio no existe en el historial.');
        }

        return $pdf->stream('RAPTOR-servicio-'.$id.'.pdf');
    }
}
