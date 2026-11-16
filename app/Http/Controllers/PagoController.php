<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoRequest;
use App\Service\PagoService;
use Exception;
use Illuminate\Validation\ValidationException;

class PagoController extends Controller
{
    protected $pagoService;

    public function __construct(PagoService $pagoService)
    {
        $this->pagoService = $pagoService;
    }

    public function index()
    {
        $pagos = $this->pagoService->listartodo();

        $resumenRecepciones = $this->pagoService->resumenRecepciones();

        return view('pago.index', compact(
            'pagos',
            'resumenRecepciones'
        ));
    }

    public function create()
    {
        $preordenes = $this->pagoService
            ->preordenesDisponiblesParaPago();

        $idPreorden = request()->query('id_preorden');
        $idOrdenTrabajo = request()->query('id_ordentrabajo');

        // Si se ingresa desde una orden de trabajo, obtener su preorden.
        if ($idOrdenTrabajo) {
            $preordenSeleccionada = $this->pagoService
                ->buscarPreordenAprobadaPorOrdenTrabajo(
                    (int) $idOrdenTrabajo
                );

            if (!$preordenSeleccionada) {
                return redirect()
                    ->route('pago.index')
                    ->withErrors([
                        'pago' => 'La orden de trabajo seleccionada no tiene una preorden aprobada asociada.',
                    ]);
            }

            $idPreorden = $preordenSeleccionada->id;

            // Comprobar que la preorden tenga saldo pendiente.
            if (!$preordenes->contains('id', (int) $idPreorden)) {
                return redirect()
                    ->route('pago.index')
                    ->withErrors([
                        'pago' => 'La recepción seleccionada no tiene saldo pendiente.',
                    ]);
            }
        }

        // Comprobar que la preorden seleccionada esté disponible.
        if (
            $idPreorden
            && !$preordenes->contains('id', (int) $idPreorden)
        ) {
            return redirect()
                ->route('pago.index')
                ->withErrors([
                    'pago' => 'La recepción seleccionada no tiene saldo pendiente o no está disponible para registrar pagos.',
                ]);
        }

        return view('pago.create', compact(
            'preordenes',
            'idPreorden',
            'idOrdenTrabajo'
        ));
    }

    public function store(PagoRequest $request)
    {
        $datos = $request->validated();

        try {
            $this->pagoService->store($datos);

            return redirect()
                ->route('pago.index')
                ->with('success', 'Pago registrado correctamente');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'valor' => $e->getMessage()
                ]);
        }
    }

    public function edit(int $id)
    {
        $pago = $this->pagoService->edit($id);

        $preordenes = $this->pagoService
            ->preordenesConRelaciones();

        return view(
            'pago.update',
            compact('pago', 'preordenes')
        );
    }

    public function update(PagoRequest $request, int $id)
    {
        $datos = $request->validated();

        try {
            $this->pagoService->update($id, $datos);

            return redirect()
                ->route('pago.index')
                ->with('update', 'Pago actualizado correctamente');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'valor' => $e->getMessage()
                ]);
        }
    }

    public function confirmar(int $id)
    {
        try {
            $this->pagoService->confirmar($id);

            return redirect()
                ->route('pago.index')
                ->with('success', 'Pago confirmado correctamente');

        } catch (ValidationException $e) {
            return redirect()
                ->route('pago.index')
                ->withErrors($e->errors());

        } catch (Exception $e) {
            return redirect()
                ->route('pago.index')
                ->withErrors([
                    'pago' => $e->getMessage()
                ]);
        }
    }

    public function destroy(int $id)
    {
        $this->pagoService->destroy($id);

        return redirect()
            ->route('pago.index')
            ->with('destroy', 'Pago eliminado correctamente');
    }
}
