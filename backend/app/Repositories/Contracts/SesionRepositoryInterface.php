<?php

namespace App\Repositories\Contracts;

use App\Models\Sesion;
use Illuminate\Database\Eloquent\Collection;

interface SesionRepositoryInterface
{
    public function todasPorGrupo(int $idGrupo): Collection;
    public function buscarPorId(int $id): ?Sesion;
    public function buscarActivaPorGrupo(int $idGrupo): ?Sesion;
    public function crear(array $datos): Sesion;
    public function guardar(Sesion $sesion, array $datos): bool;
}