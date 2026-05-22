<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use App\Repositories\Contracts\InstitucionRepositoryInterface;
use App\Services\GrupoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Controlador Web — Gestión de Grupos/Aulas del Docente.
 * @version 1.1.0
 */
class GrupoWebController extends Controller
{
    public function __construct(
        private readonly GrupoService                  $grupos,
        private readonly GrupoRepositoryInterface      $repo,
        private readonly InstitucionRepositoryInterface $instituciones,
    ) {}

    public function index()
    {
        $institucionId = session('institucion_id');

        // Si no hay institución activa, redirigir a seleccionar una
        if (!$institucionId) {
            return redirect()->route('ca.instituciones.index')
                ->with('info', 'Selecciona una institución para ver sus aulas');
        }

        $grupos = $this->repo->todosPorInstitucion($institucionId);
        return view('modules.grupos.index', compact('grupos'));
    }

    public function create()
    {
        $instituciones = $this->instituciones->todasPorDocente(Auth::user()->id_usuario);
        return view('modules.grupos.create', compact('instituciones'));
    }

    public function store(Request $request)
    {
        try {
            $this->grupos->crear($request->all(), Auth::user());
            return redirect()->route('ca.grupos.index')
                ->with('success', 'La información se registró correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit(Grupo $grupo)
    {
        $instituciones = $this->instituciones->todasPorDocente(Auth::user()->id_usuario);
        return view('modules.grupos.edit', compact('grupo', 'instituciones'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        try {
            $this->grupos->actualizar($grupo, $request->all(), Auth::user());
            return redirect()->route('ca.grupos.index')
                ->with('success', 'La información se actualizó correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy(Grupo $grupo)
    {
        $this->grupos->eliminar($grupo, Auth::user());
        return redirect()->route('ca.grupos.index')
            ->with('success', 'El registro se eliminó correctamente');
    }

    public function generarCodigo(Grupo $grupo)
    {
        $this->grupos->generarCodigoInv($grupo, Auth::user());
        return redirect()->route('ca.grupos.index')
            ->with('success', 'Código de invitación generado correctamente');
    }
}
