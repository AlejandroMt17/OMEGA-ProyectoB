<?php

/*
 * ============================================================
 * DashboardController
 * MPL-OMEGA-05 | Código: CA-CTRL-DASHBOARD-01
 * ============================================================
 * Controlador HTTP para las métricas del dashboard del Docente.
 * Sin lógica de negocio: delega al DashboardService.
 *
 * Requerimientos: RF-13, RF-76, RF-77
 * ============================================================
 */

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard
    ) {}

    /**
     * RF-76 — Resumen del dashboard: tarjetas + sesiones recientes + alumnos en riesgo.
     * GET /api/dashboard
     */
    public function resumen(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboard->resumen($request->user()),
        ]);
    }

    /**
     * RF-77 — Estado de alumnos vs rubros de evaluación de un grupo.
     * GET /api/grupos/{idGrupo}/reporte-alumnos
     */
    public function estadoAlumnos(Request $request, int $idGrupo): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboard->estadoAlumnosPorGrupo($idGrupo, $request->user()),
        ]);
    }
}
