<?php

use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\GrupoWebController;
use App\Http\Controllers\Web\InstitucionWebController;
use App\Http\Controllers\Web\JustificanteWebController;
use App\Http\Controllers\Web\PerfilWebController;
use App\Http\Controllers\Web\ReporteWebController;
use App\Http\Controllers\Web\SesionWebController;
use App\Http\Controllers\Web\SuscripcionWebController;

use Illuminate\Support\Facades\Route;
/*
 * Rutas Web — Sistema de Control de Asistencias
 * Prefijo: /p/ca
 */

// Ruta raíz
Route::get('/', function () {
    return redirect()->route('ca.login');
});

// Rutas públicas
Route::prefix('p/ca')->group(function () {

    // Autenticación
    Route::get('login',    [AuthWebController::class, 'showLogin'])->name('ca.login');
    Route::post('login',   [AuthWebController::class, 'login'])->name('ca.login.post');
    Route::get('registro', [AuthWebController::class, 'showRegistro'])->name('ca.registro');
    Route::post('registro',[AuthWebController::class, 'registro'])->name('ca.registro.post');

    // Rutas protegidas
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthWebController::class, 'logout'])->name('ca.logout');

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('ca.dashboard.index');

        // Perfil
        Route::get('perfil',                        [PerfilWebController::class, 'index'])->name('ca.perfil.index');
        Route::put('perfil',                        [PerfilWebController::class, 'actualizar'])->name('ca.perfil.actualizar');
        Route::put('perfil/contrasenia',            [PerfilWebController::class, 'cambiarContrasenia'])->name('ca.perfil.contrasenia');

        // Instituciones
        Route::get('instituciones',                        [InstitucionWebController::class, 'index'])->name('ca.instituciones.index');
        Route::get('instituciones/crear',                  [InstitucionWebController::class, 'create'])->name('ca.instituciones.create');
        Route::post('instituciones',                       [InstitucionWebController::class, 'store'])->name('ca.instituciones.store');
        Route::get('instituciones/{institucion}/editar',   [InstitucionWebController::class, 'edit'])->name('ca.instituciones.edit');
        Route::put('instituciones/{institucion}',          [InstitucionWebController::class, 'update'])->name('ca.instituciones.update');
        Route::delete('instituciones/{institucion}',       [InstitucionWebController::class, 'destroy'])->name('ca.instituciones.destroy');
        Route::post('instituciones/{id}/seleccionar',      [InstitucionWebController::class, 'seleccionar'])->name('ca.instituciones.seleccionar');

        // Grupos
        Route::get('grupos',                           [GrupoWebController::class, 'index'])->name('ca.grupos.index');
        Route::get('grupos/crear',                     [GrupoWebController::class, 'create'])->name('ca.grupos.create');
        Route::post('grupos',                          [GrupoWebController::class, 'store'])->name('ca.grupos.store');
        Route::get('grupos/{grupo}/editar',            [GrupoWebController::class, 'edit'])->name('ca.grupos.edit');
        Route::put('grupos/{grupo}',                   [GrupoWebController::class, 'update'])->name('ca.grupos.update');
        Route::delete('grupos/{grupo}',                [GrupoWebController::class, 'destroy'])->name('ca.grupos.destroy');
        Route::post('grupos/{grupo}/codigo-inv',       [GrupoWebController::class, 'generarCodigo'])->name('ca.grupos.codigo-inv');

        // Sesiones
        Route::get('grupos/{grupo}/sesiones',          [SesionWebController::class, 'index'])->name('ca.grupos.sesiones');
        Route::post('grupos/{grupo}/sesiones/abrir',   [SesionWebController::class, 'abrir'])->name('ca.grupos.sesiones.abrir');
        Route::post('sesiones/{sesion}/cerrar',        [SesionWebController::class, 'cerrar'])->name('ca.sesiones.cerrar');
        Route::get('sesiones/{sesion}/asistencias',    [SesionWebController::class, 'asistencias'])->name('ca.sesiones.asistencias');

        // Justificantes
        Route::get('justificantes',                              [JustificanteWebController::class, 'index'])->name('ca.justificantes.index');
        Route::post('justificantes/{asistencia}/justificar',     [JustificanteWebController::class, 'justificar'])->name('ca.justificantes.justificar');
        Route::post('justificantes/{asistencia}/marcar-ausente', [JustificanteWebController::class, 'marcarAusente'])->name('ca.justificantes.ausente');

        // Reportes
        Route::get('reportes',                [ReporteWebController::class, 'index'])->name('ca.reportes.index');
        Route::get('reportes/{idGrupo}',      [ReporteWebController::class, 'detalle'])->name('ca.reportes.detalle');


        // Suscripción
        Route::get('suscripcion', [SuscripcionWebController::class, 'index'])->name('ca.suscripcion.index');
        Route::post('suscripcion/crear-orden', [SuscripcionWebController::class, 'crearOrden'])
        ->name('ca.suscripcion.crear-orden');
    });
});