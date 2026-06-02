<?php

namespace App\Repositories\Contracts;

use App\Models\Pago;
use Illuminate\Database\Eloquent\Collection;

interface PagoRepositoryInterface
{
    public function todosPorSuscripcion(int $idSuscripcion): Collection;
    public function buscarPorOrderId(string $orderId): ?Pago;
    public function crear(array $datos): Pago;
    public function guardar(Pago $pago, array $datos): bool;
}