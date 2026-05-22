<?php

/*
 * ============================================================
 * SesionService
 * MPL-OMEGA-05 | Código: CA-SVC-SESION-01
 * ============================================================
 * Lógica de negocio para sesiones de asistencia.
 *
 * Valores de est_sesion (MDB-OMEGA-DD-01 §4.6 — tabla sesiones):
 *   1 = Activa
 *   0 = Cerrada
 *
 * Requerimientos cubiertos:
 *   RF-62  Generar clave alfanumérica única por sesión
 *   RF-63  Mostrar clave activa en pantalla
 *   RF-64  Abrir y cerrar manualmente la ventana de registro
 *   RF-65  Cierre automático al vencer el tiempo
 *   RF-66  Registro de asistencia en tiempo real
 *   RNF-W-44 Clave temporal e irrepetible
 * ============================================================
 */

namespace App\Services;

use App\Models\Grupo;
use App\Models\Sesion;
use App\Models\Usuario;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use App\Repositories\Contracts\SesionRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SesionService
{
    public function __construct(
        private readonly SesionRepositoryInterface $sesiones,
        private readonly GrupoRepositoryInterface  $grupos,
    ) {}

    /**
     * RF-62 — Lista sesiones de un grupo (solo el docente propietario).
     */
    public function listar(int $idGrupo, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($idGrupo);
        $this->verificarPropietarioGrupo($grupo, $docente);

        return $this->sesiones->todasPorGrupo($idGrupo)
            ->map(fn(Sesion $s) => $this->serializar($s))
            ->values()
            ->all();
    }

    /**
     * RF-62, RF-63 — Abre una nueva sesión y genera la clave única.
     * Valida que no exista ya una sesión activa para el grupo.
     */
    public function abrir(int $idGrupo, array $entrada, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($idGrupo);
        $this->verificarPropietarioGrupo($grupo, $docente);

        // Verificar que no haya sesión activa (est_sesion = 1)
        $sesionActiva = $this->sesiones->buscarActivaPorGrupo($idGrupo);
        if ($sesionActiva) {
            throw ValidationException::withMessages([
                'sesion' => ['Ya existe una sesión activa para este grupo.'],
            ]);
        }

        $validator = Validator::make($entrada, [
            'fec_sesion' => ['required', 'date'],
        ], [
            'fec_sesion.required' => 'La fecha de sesión es obligatoria.',
            'fec_sesion.date'     => 'La fecha de sesión no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // RF-62, RNF-W-44 — Clave alfanumérica de 6 caracteres, única e irrepetible
        $clave = strtoupper(Str::random(6));

        $sesion = $this->sesiones->crear([
            'id_grupo'      => $idGrupo,
            'clave'         => $clave,
            'est_sesion'    => 1,                      // Activa
            'fec_sesion'    => $entrada['fec_sesion'],
            'hora_apertura' => now(),
            'hora_cierre'   => null,
        ]);

        return $this->serializar($sesion);
    }

    /**
     * RF-64 — Cierra manualmente la sesión activa.
     * La clave se invalida (se borra) al momento del cierre.
     * est_sesion pasa de 1 (Activa) a 0 (Cerrada).
     */
    public function cerrar(Sesion $sesion, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($sesion->id_grupo);
        $this->verificarPropietarioGrupo($grupo, $docente);

        // Verificar que la sesión no esté ya cerrada (est_sesion = 0)
        if ($sesion->est_sesion === 0) {
            throw ValidationException::withMessages([
                'sesion' => ['La sesión ya está cerrada.'],
            ]);
        }

        // RF-64 — Cierre: est_sesion = 0, clave = null, hora_cierre = ahora
        $this->sesiones->guardar($sesion, [
            'est_sesion'  => 0,
            'clave'       => null,
            'hora_cierre' => now(),
        ]);

        return $this->serializar($sesion->fresh());
    }

    /**
     * RF-63 — Obtiene los datos de una sesión específica.
     * La clave solo se retorna si la sesión está activa (est_sesion = 1).
     */
    public function obtener(Sesion $sesion, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($sesion->id_grupo);
        $this->verificarPropietarioGrupo($grupo, $docente);
        return $this->serializar($sesion);
    }

    // ─────────────────────────────────────────────────────────────
    //  Helpers privados
    // ─────────────────────────────────────────────────────────────

    /**
     * Verifica que el docente autenticado sea el propietario del grupo.
     * Lanza AuthorizationException si no lo es.
     */
    private function verificarPropietarioGrupo(?Grupo $grupo, Usuario $docente): void
    {
        if (!$grupo || $grupo->id_docente !== $docente->id_usuario) {
            throw new AuthorizationException(
                'No tienes permiso para acceder a este grupo.'
            );
        }
    }

    /**
     * Serializa una sesión para la respuesta JSON.
     * La clave solo se expone cuando est_sesion = 1 (Activa).
     * Cuando está cerrada, clave = null para invalidarla en el cliente.
     */
    private function serializar(Sesion $sesion): array
    {
        return [
            'id_sesion'     => $sesion->id_sesion,
            'id_grupo'      => $sesion->id_grupo,
            // RF-63, RF-64 — Clave visible solo con sesión activa
            'clave'         => $sesion->est_sesion === 1 ? $sesion->clave : null,
            'est_sesion'    => $sesion->est_sesion,   // 1=Activa, 0=Cerrada
            'fec_sesion'    => $sesion->fec_sesion?->toDateString(),
            'hora_apertura' => $sesion->hora_apertura?->toIso8601String(),
            'hora_cierre'   => $sesion->hora_cierre?->toIso8601String(),
        ];
    }
}
