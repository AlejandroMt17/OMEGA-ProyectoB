<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Sesion;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador Web — Reportes de asistencia por grupo.
 * @version 1.0.0
 */
class ReporteWebController extends Controller
{
    public function __construct(
        private readonly GrupoRepositoryInterface $grupos,
    ) {}

    public function index()
    {
        $docente = Auth::user();
        $grupos  = $this->grupos->todosPorDocente($docente->id_usuario);

        // Calcular estadísticas por grupo
        $reportes = $grupos->map(function ($grupo) {
            $grupoIds   = [$grupo->id_grupo];
            $sesiones   = Sesion::whereIn('id_grupo', $grupoIds)->get();
            $sesionIds  = $sesiones->pluck('id_sesion');

            $totalSesiones   = $sesiones->count();
            $totalPresentes  = Asistencia::whereIn('id_sesion', $sesionIds)->where('est_asistencia', 1)->count();
            $totalAusentes   = Asistencia::whereIn('id_sesion', $sesionIds)->where('est_asistencia', 2)->count();
            $totalJustif     = Asistencia::whereIn('id_sesion', $sesionIds)->where('est_asistencia', 3)->count();
            $totalAsistencias = $totalPresentes + $totalAusentes + $totalJustif;

            $porcentaje = $totalAsistencias > 0
                ? round(($totalPresentes / $totalAsistencias) * 100, 1)
                : 0;

            return [
                'grupo'           => $grupo,
                'total_sesiones'  => $totalSesiones,
                'total_presentes' => $totalPresentes,
                'total_ausentes'  => $totalAusentes,
                'total_justif'    => $totalJustif,
                'porcentaje'      => $porcentaje,
            ];
        });

        return view('modules.reportes.index', compact('reportes'));
    }

    public function detalle(int $idGrupo)
    {
        $docente = Auth::user();
        $grupo   = $this->grupos->buscarPorId($idGrupo);

        $sesiones = Sesion::where('id_grupo', $idGrupo)
            ->orderByDesc('fec_sesion')
            ->get()
            ->map(function ($sesion) {
                $presentes = Asistencia::where('id_sesion', $sesion->id_sesion)->where('est_asistencia', 1)->count();
                $ausentes  = Asistencia::where('id_sesion', $sesion->id_sesion)->where('est_asistencia', 2)->count();
                $justif    = Asistencia::where('id_sesion', $sesion->id_sesion)->where('est_asistencia', 3)->count();
                return [
                    'sesion'    => $sesion,
                    'presentes' => $presentes,
                    'ausentes'  => $ausentes,
                    'justif'    => $justif,
                ];
            });

        return view('modules.reportes.detalle', compact('grupo', 'sesiones'));
    }
}