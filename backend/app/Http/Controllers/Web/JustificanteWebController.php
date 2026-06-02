<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Repositories\Contracts\AsistenciaRepositoryInterface;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use App\Services\AsistenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Controlador Web — Gestión de Justificantes.
 * @version 1.0.0
 */
class JustificanteWebController extends Controller
{
    public function __construct(
        private readonly AsistenciaRepositoryInterface $asistencias,
        private readonly GrupoRepositoryInterface      $grupos,
        private readonly AsistenciaService             $asistenciaService,
    ) {}

    public function index()
    {
        // Obtener todas las ausencias de los grupos del docente
        $gruposIds = $this->grupos
            ->todosPorDocente(Auth::user()->id_usuario)
            ->pluck('id_grupo');

        $ausencias = Asistencia::query()
            ->whereIn('est_asistencia', [2, 3])
            ->whereHas('sesion', function ($q) use ($gruposIds) {
                $q->whereIn('id_grupo', $gruposIds);
            })
            ->with(['alumno', 'sesion.grupo'])
            ->orderByDesc('id_asistencia')
            ->get();

        return view('modules.justificantes.index', compact('ausencias'));
    }

    public function justificar(Asistencia $asistencia)
    {
        try {
            $this->asistenciaService->editarEstado($asistencia, [
                'est_asistencia' => 3, // Justificada
            ]);

            return redirect()->route('ca.justificantes.index')
                ->with('success', 'La información se actualizó correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    public function marcarAusente(Asistencia $asistencia)
    {
        try {
            $this->asistenciaService->editarEstado($asistencia, [
                'est_asistencia' => 2, // Ausente
            ]);

            return redirect()->route('ca.justificantes.index')
                ->with('success', 'La información se actualizó correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}