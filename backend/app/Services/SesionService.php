<?php

/*
 * ============================================================
 * SesionService
 * MPL-OMEGA-05 | Código: CA-SVC-SESION-01
 * ============================================================
 * Lógica de negocio para sesiones de asistencia.
 *
 * Valores de est_sesion (MDB-OMEGA-DD-01 §4.6):
 *   1 = Activa
 *   0 = Cerrada
 *
 * Requerimientos cubiertos:
 *   RF-62  Generar clave alfanumérica única por sesión
 *   RF-63  Mostrar clave activa — consultar sesión activa del grupo
 *   RF-64  Abrir y cerrar manualmente la ventana de registro
 *   RF-65  Cierre automático al vencer el tiempo
 *   RF-66  Registro en tiempo real con actualizaciones
 *   RF-48  Temporizador: hora_apertura disponible en respuesta
 *   RF-49  Estadísticas en tiempo real: presentes / total alumnos
 *   RNF-W-44 Clave temporal e irrepetible
 * ============================================================
 */

namespace App\Services;

use App\Models\Grupo;
use App\Models\Sesion;
use App\Models\Usuario;
use App\Repositories\Contracts\AsistenciaRepositoryInterface;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use App\Repositories\Contracts\GrupoAlumnoRepositoryInterface;
use App\Repositories\Contracts\SesionRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SesionService
{
    public function __construct(
        private readonly SesionRepositoryInterface      $sesiones,
        private readonly GrupoRepositoryInterface       $grupos,
        private readonly AsistenciaRepositoryInterface  $asistencias,
        private readonly GrupoAlumnoRepositoryInterface $grupoAlumnos,
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
     * RF-63 — Consulta la sesión activa de un grupo.
     * Usada por Flutter para saber si ya hay una sesión abierta
     * y mostrar la clave y el temporizador (RF-47, RF-48).
     * Retorna null si no hay sesión activa.
     */
    public function sesionActivaDelGrupo(int $idGrupo, Usuario $docente): ?array
    {
        $grupo = $this->grupos->buscarPorId($idGrupo);
        $this->verificarPropietarioGrupo($grupo, $docente);

        $sesion = $this->sesiones->buscarActivaPorGrupo($idGrupo);

        if (!$sesion) {
            return null;
        }

        return $this->serializarConEstadisticas($sesion);
    }

    /**
     * RF-62, RF-63 — Abre una nueva sesión y genera la clave única.
     */
    public function abrir(int $idGrupo, array $entrada, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($idGrupo);
        $this->verificarPropietarioGrupo($grupo, $docente);

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
            'est_sesion'    => 1,
            'fec_sesion'    => $entrada['fec_sesion'],
            'hora_apertura' => now(),
            'hora_cierre'   => null,
        ]);

        return $this->serializarConEstadisticas($sesion);
    }

    /**
     * RF-64 — Cierra manualmente la sesión activa.
     * La clave se invalida (null) al cerrar. est_sesion = 0.
     */
    public function cerrar(Sesion $sesion, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($sesion->id_grupo);
        $this->verificarPropietarioGrupo($grupo, $docente);

        if ($sesion->est_sesion === 0) {
            throw ValidationException::withMessages([
                'sesion' => ['La sesión ya está cerrada.'],
            ]);
        }

        $this->sesiones->guardar($sesion, [
            'est_sesion'  => 0,
            'clave'       => null,
            'hora_cierre' => now(),
        ]);

        return $this->serializar($sesion->fresh());
    }

    /**
     * RF-63 — Obtiene datos de una sesión específica con estadísticas.
     */
    public function obtener(Sesion $sesion, Usuario $docente): array
    {
        $grupo = $this->grupos->buscarPorId($sesion->id_grupo);
        $this->verificarPropietarioGrupo($grupo, $docente);
        return $this->serializarConEstadisticas($sesion);
    }

    // ─────────────────────────────────────────────────────────────
    //  Helpers privados
    // ─────────────────────────────────────────────────────────────

    private function verificarPropietarioGrupo(?Grupo $grupo, Usuario $docente): void
    {
        if (!$grupo || $grupo->id_docente !== $docente->id_usuario) {
            throw new AuthorizationException(
                'No tienes permiso para acceder a este grupo.'
            );
        }
    }

    /**
     * Serialización base — sin estadísticas (para listas).
     * La clave solo se expone cuando est_sesion = 1 (Activa).
     */
    private function serializar(Sesion $sesion): array
    {
        return [
            'id_sesion'     => $sesion->id_sesion,
            'id_grupo'      => $sesion->id_grupo,
            'clave'         => $sesion->est_sesion === 1 ? $sesion->clave : null,
            'est_sesion'    => $sesion->est_sesion,
            'fec_sesion'    => $sesion->fec_sesion?->toDateString(),
            'hora_apertura' => $sesion->hora_apertura?->toIso8601String(),
            'hora_cierre'   => $sesion->hora_cierre?->toIso8601String(),
        ];
    }

    /**
     * RF-48, RF-49 — Serialización con estadísticas en tiempo real.
     * Incluye: total_alumnos, presentes, pendientes y segundos transcurridos
     * para el temporizador del docente en Flutter.
     */
    private function serializarConEstadisticas(Sesion $sesion): array
    {
        $base        = $this->serializar($sesion);
        $asistencias = $this->asistencias->todasPorSesion($sesion->id_sesion);
        $totalGrupo  = $this->grupoAlumnos->alumnosPorGrupo($sesion->id_grupo)->count();
        $presentes   = $asistencias->where('est_asistencia', 1)->count();

        // RF-48 — Segundos transcurridos desde apertura (para el temporizador MM:SS)
        $segundos = $sesion->hora_apertura
            ? (int) $sesion->hora_apertura->diffInSeconds(now())
            : 0;

        return array_merge($base, [
            // RF-49 — Estadísticas en tiempo real
            'total_alumnos'    => $totalGrupo,
            'presentes'        => $presentes,
            'pendientes'       => max(0, $totalGrupo - $presentes),
            'porcentaje_reg'   => $totalGrupo > 0
                ? round(($presentes / $totalGrupo) * 100, 1)
                : 0.0,
            // RF-48 — Para el temporizador de Flutter
            'segundos_abierta' => $segundos,
            // Últimos 5 registros (RF-50)
            'ultimos_registros' => $asistencias
                ->where('est_asistencia', 1)
                ->sortByDesc('hora_registro')
                ->take(5)
                ->map(fn($a) => [
                    'id_alumno'     => $a->id_alumno,
                    'nombre'        => $a->alumno
                        ? "{$a->alumno->ap_pat}, {$a->alumno->nombre}"
                        : null,
                    'hora_registro' => $a->hora_registro?->format('H:i:s'),
                ])->values()->all(),
        ]);
    }
}
