<?php

namespace App\Http\Controllers;

use App\Services\PagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador HTTP — Integración con PayPal Sandbox.
 * Sin lógica de negocio, solo delega al PagoService.
 */
class PagoController extends Controller
{
    public function __construct(
        private readonly PagoService $pagos
    ) {}

    public function crearOrden(Request $request): JsonResponse
    {
        $orden = $this->pagos->crearOrden($request->user());
        return response()->json(['data' => $orden], 201);
    }

    public function capturarPago(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => ['required', 'string'],
        ]);

        $pago = $this->pagos->capturarPago($request->order_id, $request->user());
        return response()->json(['data' => $pago]);
    }

    public function historial(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->pagos->historial($request->user()),
        ]);
    }
}