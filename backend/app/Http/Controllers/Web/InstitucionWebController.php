<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Institucion;
use App\Repositories\Contracts\InstitucionRepositoryInterface;
use App\Services\InstitucionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Controlador Web — Gestión de Instituciones del Docente.
 * @version 1.1.0
 */
class InstitucionWebController extends Controller
{
    public function __construct(
        private readonly InstitucionService            $instituciones,
        private readonly InstitucionRepositoryInterface $repo,
    ) {}

    public function index()
    {
        $instituciones = $this->repo->todasPorDocente(Auth::user()->id_usuario);
        return view('modules.instituciones.index', compact('instituciones'));
    }

    public function create()
    {
        return view('modules.instituciones.create');
    }

    public function store(Request $request)
    {
        try {
            $this->instituciones->crear($request->all(), Auth::user());
            return redirect()->route('ca.instituciones.index')
                ->with('success', 'La información se registró correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit(Institucion $institucion)
    {
        $this->instituciones->obtener($institucion, Auth::user());
        return view('modules.instituciones.edit', compact('institucion'));
    }

    public function update(Request $request, Institucion $institucion)
    {
        try {
            $this->instituciones->actualizar($institucion, $request->all(), Auth::user());
            return redirect()->route('ca.instituciones.index')
                ->with('success', 'La información se actualizó correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy(Institucion $institucion)
    {
        $this->instituciones->eliminar($institucion, Auth::user());
        return redirect()->route('ca.instituciones.index')
            ->with('success', 'El registro se eliminó correctamente');
    }

    /**
     * Guarda la institución activa en sesión y redirige a Mis Aulas.
     */
    public function seleccionar(int $id)
    {
        $institucion = $this->repo->buscarPorId($id);

        if (!$institucion || $institucion->id_docente !== Auth::user()->id_usuario) {
            abort(403);
        }

        session([
            'institucion_id'     => $institucion->id_institucion,
            'institucion_nombre' => $institucion->nombre,
        ]);

        return redirect()->route('ca.grupos.index')
            ->with('success', "Institución «{$institucion->nombre}» seleccionada");
    }
}
