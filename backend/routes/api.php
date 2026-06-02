<?php

/*
 * ============================================================
 * Rutas API REST — Sistema de Control de Asistencias
 * MPL-OMEGA-05 | Prefijo automático: /api
 * ============================================================
 *
 * Grupos de rutas:
 *  [público]        — auth/registro, auth/login
 *  [auth:sanctum]
 *    ├─ Compartidas — me, logout
 *    ├─ Docente     — dashboard, instituciones, grupos, sesiones,
 *    │               asistencias (detalle+editar), rubros,
 *    │               grupo-alumnos, reportes, justificantes,
 *    │               suscripción, pagos
 *    └─ Alumno      — unirse, mis grupos, registrar asistencia,
 *                     historial por grupo
 * ============================================================
 */

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

    // ── Auth compartida ────────────────────────────────────────────────────
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // ── Dashboard del Docente (RF-76, RF-77) ───────────────────────────────
    // GET  /api/dashboard                           → tarjetas + sesiones recientes + alumnos en riesgo
    // GET  /api/grupos/{idGrupo}/reporte-alumnos    → tabla estado alumnos vs rubros
    Route::get('dashboard',                              [DashboardController::class, 'resumen']);
    Route::get('grupos/{idGrupo}/reporte-alumnos',       [DashboardController::class, 'estadoAlumnos']);

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
    // IMPORTANTE: la ruta /activa debe ir ANTES de /abrir para no colisionar
    Route::get('grupos/{idGrupo}/sesiones',         [SesionController::class, 'index']);
    Route::get('grupos/{idGrupo}/sesiones/activa',  [SesionController::class, 'activa']);  // RF-63
    Route::post('grupos/{idGrupo}/sesiones/abrir',  [SesionController::class, 'abrir']);
    Route::get('sesiones/{sesion}',                 [SesionController::class, 'show']);
    Route::post('sesiones/{sesion}/cerrar',         [SesionController::class, 'cerrar']);

    // ── Asistencias ────────────────────────────────────────────────────────
    // Vista básica (lista de IDs y estados)
    Route::get('sesiones/{idSesion}/asistencias',         [AsistenciaController::class, 'porSesion']);
    // Vista docente (con nombre completo y hora HH:MM:SS) — RF-66
    Route::get('sesiones/{idSesion}/asistencias/detalle', [AsistenciaController::class, 'porSesionConAlumnos']);
    // Porcentaje individual — RF-69
    Route::get('grupos/{idGrupo}/alumnos/{idAlumno}/porcentaje', [AsistenciaController::class, 'porcentajeAlumno']);
    // Editar estado (Presente/Ausente/Justificado) — RF-67, RF-74
    Route::put('asistencias/{asistencia}/estado',         [AsistenciaController::class, 'editarEstado']);

    // ── Suscripciones ──────────────────────────────────────────────────────
    Route::get('suscripcion',         [SuscripcionController::class, 'show']);
    Route::post('suscripcion/basico', [SuscripcionController::class, 'activarBasico']);

    // ── Pagos PayPal ───────────────────────────────────────────────────────
    Route::post('pagos/crear-orden',  [PagoController::class, 'crearOrden']);
    Route::post('pagos/capturar',     [PagoController::class, 'capturarPago']);
    Route::get('pagos/historial',     [PagoController::class, 'historial']);

    // ══════════════════════════════════════════════════════════════════════
    //  RUTAS DEL ALUMNO (app móvil Flutter)
    //  RF-14, RF-15, RF-19, RF-21, RF-31..RF-45
    // ══════════════════════════════════════════════════════════════════════

    // RF-19 — Matriculación por código de invitación
    Route::post('alumno/grupos/unirse',             [AlumnoController::class, 'unirse']);
    // RF-15 — Panel de progreso con % asistencia y rubros
    Route::get('alumno/grupos',                     [AlumnoController::class, 'misGrupos']);
    // RF-14, RF-21, RF-38 — Registro de asistencia con clave temporal
    Route::post('alumno/asistencia',                [AlumnoController::class, 'registrarAsistencia']);
    // RF-31, RF-32, RF-33 — Historial de asistencia con código de colores
    Route::get('alumno/grupos/{idGrupo}/historial', [AlumnoController::class, 'historialGrupo']);
});
