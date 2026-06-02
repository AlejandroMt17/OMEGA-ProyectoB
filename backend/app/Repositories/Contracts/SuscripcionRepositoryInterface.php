<?php

namespace App\Repositories\Contracts;

use App\Models\Suscripcion;

interface SuscripcionRepositoryInterface
{
    public function buscarPorUsuario(int $idUsuario): ?Suscripcion;
    public function buscarPorId(int $id): ?Suscripcion;
    public function crear(array $datos): Suscripcion;
    public function guardar(Suscripcion $suscripcion, array $datos): bool;
}