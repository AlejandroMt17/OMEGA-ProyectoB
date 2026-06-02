<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Services\GrupoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador HTTP — Gestión de grupos del Docente.
 * Sin lógica de negocio, solo delega al GrupoService.
 */
class GrupoController extends Controller
{
    public function __construct(
        private readonly GrupoService $grupos
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->grupos->listar($request->user()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $creado = $this->grupos->crear($request->all(), $request->user());
        return response()->json(['data' => $creado], 201);
    }

    public function show(Request $request, Grupo $grupo): JsonResponse
    {
        return response()->json([
            'data' => $this->grupos->obtener($grupo, $request->user()),
        ]);
    }

    public function update(Request $request, Grupo $grupo): JsonResponse
    {
        $actualizado = $this->grupos->actualizar($grupo, $request->all(), $request->user());
        return response()->json(['data' => $actualizado]);
    }

    public function destroy(Request $request, Grupo $grupo): JsonResponse
    {
        $this->grupos->eliminar($grupo, $request->user());
        return response()->noContent();
    }

    public function generarCodigo(Request $request, Grupo $grupo): JsonResponse
    {
        $actualizado = $this->grupos->generarCodigoInv($grupo, $request->user());
        return response()->json(['data' => $actualizado]);
    }
}