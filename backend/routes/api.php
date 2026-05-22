<?php

/*
 * ============================================================
 * Rutas API REST — Sistema de Control de Asistencias
 * MPL-OMEGA-05 | Prefijo automático: /api
 * ============================================================
 *
 * Convenciones (Manual de Programación Laravel §3.6):
 *  - snake_case en segmentos de ruta
 *  - verbos HTTP semánticos (GET/POST/PUT/DELETE)
 *  - parámetros con el mismo nombre que la PK del recurso
 *
 * Grupos de rutas:
 *  [público]           — auth/registro, auth/login
 *  [auth:sanctum]      — todo lo demás
 *    ├─ Compartidas    — me, logout
 *    ├─ Docente        — instituciones, grupos, sesiones (abrir/cerrar),
 *    │                   asistencias (editar), rubros, grupo-alumnos (gestión),
 *    │                   reportes, justificantes, suscripción, pagos
 *    └─ Alumno         — registro asistencia por clave, panel de progreso,
 *                        mis grupos, mis materias, matriculación por código
 * ============================================================
 */

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GrupoAlumnoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\RubroEvaluacionController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

// ─── Rutas públicas ────────────────────────────────────────────────────────
Route::post('auth/registro', [AuthController::class, 'registro']);
Route::post('auth/login',    [AuthController::class, 'login']);

// ─── Rutas protegidas con Sanctum ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth compartida
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // ── Usuarios (administración) ──────────────────────────────────────────
    Route::get('usuarios',              [UsuarioController::class, 'index']);
    Route::post('usuarios',             [UsuarioController::class, 'store']);
    Route::get('usuarios/{usuario}',    [UsuarioController::class, 'show']);
    Route::put('usuarios/{usuario}',    [UsuarioController::class, 'update']);
    Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy']);

    // ── Instituciones ──────────────────────────────────────────────────────
    Route::get('instituciones',                  [InstitucionController::class, 'index']);
    Route::post('instituciones',                 [InstitucionController::class, 'store']);
    Route::get('instituciones/{institucion}',    [InstitucionController::class, 'show']);
    Route::put('instituciones/{institucion}',    [InstitucionController::class, 'update']);
    Route::delete('instituciones/{institucion}', [InstitucionController::class, 'destroy']);

    // ── Rubros de evaluación ───────────────────────────────────────────────
    Route::get('instituciones/{idInstitucion}/rubros',  [RubroEvaluacionController::class, 'index']);
    Route::post('instituciones/{idInstitucion}/rubros', [RubroEvaluacionController::class, 'store']);
    Route::put('rubros/{rubroEvaluacion}',              [RubroEvaluacionController::class, 'update']);
    Route::delete('rubros/{rubroEvaluacion}',           [RubroEvaluacionController::class, 'destroy']);

    // ── Grupos ─────────────────────────────────────────────────────────────
    Route::get('grupos',                     [GrupoController::class, 'index']);
    Route::post('grupos',                    [GrupoController::class, 'store']);
    Route::get('grupos/{grupo}',             [GrupoController::class, 'show']);
    Route::put('grupos/{grupo}',             [GrupoController::class, 'update']);
    Route::delete('grupos/{grupo}',          [GrupoController::class, 'destroy']);
    Route::post('grupos/{grupo}/codigo-inv', [GrupoController::class, 'generarCodigo']);

    // ── Alumnos en grupos (gestión docente) ────────────────────────────────
    Route::get('grupos/{idGrupo}/alumnos',       [GrupoAlumnoController::class, 'index']);
    Route::delete('grupo-alumnos/{grupoAlumno}', [GrupoAlumnoController::class, 'destroy']);

    // ── Sesiones ───────────────────────────────────────────────────────────
    Route::get('grupos/{idGrupo}/sesiones',        [SesionController::class, 'index']);
    Route::post('grupos/{idGrupo}/sesiones/abrir', [SesionController::class, 'abrir']);
    Route::get('sesiones/{sesion}',                [SesionController::class, 'show']);
    Route::post('sesiones/{sesion}/cerrar',        [SesionController::class, 'cerrar']);

    // ── Asistencias (docente) ──────────────────────────────────────────────
    Route::get('sesiones/{idSesion}/asistencias',  [AsistenciaController::class, 'porSesion']);
    Route::put('asistencias/{asistencia}/estado',  [AsistenciaController::class, 'editarEstado']);

    // ── Suscripciones ──────────────────────────────────────────────────────
    Route::get('suscripcion',         [SuscripcionController::class, 'show']);
    Route::post('suscripcion/basico', [SuscripcionController::class, 'activarBasico']);

    // ── Pagos PayPal ───────────────────────────────────────────────────────
    Route::post('pagos/crear-orden',  [PagoController::class, 'crearOrden']);
    Route::post('pagos/capturar',     [PagoController::class, 'capturarPago']);
    Route::get('pagos/historial',     [PagoController::class, 'historial']);

    // ══════════════════════════════════════════════════════════════════════
    //  RUTAS DEL ALUMNO (app móvil Flutter)
    //  Integradas desde OMEGA-FINAL — RF-14, RF-15, RF-19, RF-31..RF-45
    // ══════════════════════════════════════════════════════════════════════

    // RF-19 — Matriculación por código de invitación o QR temporal
    // POST /api/alumno/grupos/unirse   body: { "codigo_inv": "XXXXXXXX" }
    Route::post('alumno/grupos/unirse', [AlumnoController::class, 'unirse']);

    // RF-15 — Panel de progreso: lista de materias con % asistencia y rubros
    // GET  /api/alumno/grupos
    Route::get('alumno/grupos', [AlumnoController::class, 'misGrupos']);

    // RF-14, RF-21, RF-38 — Registro de asistencia con clave temporal
    // POST /api/alumno/asistencia   body: { "id_grupo": 1, "clave": "ABC123" }
    Route::post('alumno/asistencia', [AlumnoController::class, 'registrarAsistencia']);

    // RF-31, RF-32, RF-33 — Historial de asistencia por materia con colores
    // GET  /api/alumno/grupos/{idGrupo}/historial
    Route::get('alumno/grupos/{idGrupo}/historial', [AlumnoController::class, 'historialGrupo']);
});
