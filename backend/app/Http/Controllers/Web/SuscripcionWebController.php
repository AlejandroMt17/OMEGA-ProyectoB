<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PagoService;
use App\Services\SuscripcionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador Web — Vista de suscripción del Docente.
 * @version 1.0.0
 */
class SuscripcionWebController extends Controller
{
    public function __construct(
        private readonly SuscripcionService $suscripciones,
        private readonly PagoService        $pagos,
    ) {}

    public function index(Request $request)
    {
        // PayPal regresa aquí con ?token=ORDER_ID&PayerID=XXX tras aprobar el pago
        if ($request->query('status') === 'success' && $request->query('token')) {
            try {
                $this->pagos->capturarPago($request->query('token'), Auth::user());
                session()->flash('pago_exitoso', 'Tu Plan Mensual ha sido activado correctamente.');
            } catch (\Exception $e) {
                session()->flash('pago_error', 'El pago no pudo completarse. Intenta de nuevo.');
            }

            // Redirige limpiando los query params de PayPal de la URL
            return redirect()->route('ca.suscripcion.index');
        }

        $suscripcion = $this->suscripciones->obtener(Auth::user());
        return view('modules.suscripcion.index', compact('suscripcion'));
    }

    public function crearOrden(): JsonResponse
    {
        try {
            $data = $this->pagos->crearOrden(Auth::user());
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se pudo crear la orden de pago.'], 500);
        }
    }
}
