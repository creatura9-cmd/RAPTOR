<?php

namespace App\Service;

use App\Repository\DashboardRepository;

class DashboardService
{
    protected DashboardRepository $dashboardRepository;

    public function __construct(
        DashboardRepository $dashboardRepository
    ) {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function obtenerDatosDashboard(): array
    {
        $seguimientos = $this->dashboardRepository->obtenerSeguimientos();
        $estadisticas = $this->dashboardRepository->obtenerEstadisticas();

        $estadisticas['pagos_completos'] = $seguimientos
            ->filter(function ($servicio) {
                return $servicio->orden !== null
                    && $servicio->preorden !== null
                    && $servicio->preorden->estado === 'aprobada'
                    && $servicio->total_aprobado > 0
                    && $servicio->saldo <= 0;
            })
            ->count();

        return [
            'estadisticas' => $estadisticas,
            'ordenesPorEstado' => $this->dashboardRepository->obtenerOrdenesPorEstado(),
            'preordenesPorEstado' => $this->dashboardRepository->obtenerPreordenesPorEstado(),
            'recepciones' => $this->dashboardRepository->obtenerRecepciones(),
            'seguimientos' => $seguimientos,
            'actividadesRecientes' => $this->dashboardRepository->obtenerActividadesRecientes(),
        ];
    }
}