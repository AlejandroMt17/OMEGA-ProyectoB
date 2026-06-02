<?php

namespace App\Repositories\Contracts;

use App\Models\RubroEvaluacion;
use Illuminate\Database\Eloquent\Collection;

interface RubroEvaluacionRepositoryInterface
{
    public function todosPorInstitucion(int $idInstitucion): Collection;
    public function buscarPorId(int $id): ?RubroEvaluacion;
    public function crear(array $datos): RubroEvaluacion;
    public function guardar(RubroEvaluacion $rubro, array $datos): bool;
    public function eliminar(RubroEvaluacion $rubro): bool;
}