<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use App\Repositories\Contracts\SesionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador Web — Dashboard principal del Docente.
 * @version 1.0.0
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly GrupoRepositoryInterface  $grupos,
        private readonly SesionRepositoryInterface $sesiones,
    ) {}

    public function index()
    {
        $docente = Auth::user();

        // Una sola query para evitar duplicados
        $grupos      = $this->grupos->todosPorDocente($docente->id_usuario);
        $aulasActivas = $grupos->count();
        $gruposIds   = $grupos->pluck('id_grupo');

        $sesionesHoy = \App\Models\Sesion::query()
            ->whereIn('id_grupo', $gruposIds)
            ->whereDate('fec_sesion', today())
            ->count();

        return view('modules.dashboard.index', compact(
            'aulasActivas',
            'sesionesHoy',
        ));
    }
}