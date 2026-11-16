<?php

namespace App\Http\Controllers;

use App\Service\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(
        DashboardService $dashboardService
    ) {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $secciones = [
            'inicio' => 'Inicio',
            'recepcion' => 'Recepción',
            'taller' => 'Taller',
            'seguimiento' => 'Seguimiento',
            'administracion' => 'Administración',
        ];

        $seccion = $request->query('seccion', 'inicio');

        if (! array_key_exists($seccion, $secciones)) {
            $seccion = 'inicio';
        }

        $datos = $this->dashboardService
            ->obtenerDatosDashboard();

        return view('dashboard.index', array_merge(
            [
                'secciones' => $secciones,
                'seccion' => $seccion,
            ],
            $datos
        ));
    }
}