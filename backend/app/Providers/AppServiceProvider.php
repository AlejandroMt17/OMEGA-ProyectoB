<?php

/*
 * ============================================================
 * AppServiceProvider
 * MPL-OMEGA-05 §2.3 — Capa de Repositorios (Encapsulamiento)
 * ============================================================
 * Vincula cada interfaz de repositorio con su implementación
 * concreta. AlumnoService no necesita binding propio porque
 * Laravel lo resuelve automáticamente por inyección de
 * dependencias (sus dependencias son todas interfaces ya
 * registradas aquí).
 * ============================================================
 */

namespace App\Providers;

use App\Repositories\Contracts\AsistenciaRepositoryInterface;
use App\Repositories\Contracts\GrupoAlumnoRepositoryInterface;
use App\Repositories\Contracts\GrupoRepositoryInterface;
use App\Repositories\Contracts\InstitucionRepositoryInterface;
use App\Repositories\Contracts\PagoRepositoryInterface;
use App\Repositories\Contracts\RubroEvaluacionRepositoryInterface;
use App\Repositories\Contracts\SesionRepositoryInterface;
use App\Repositories\Contracts\SuscripcionRepositoryInterface;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use App\Repositories\AsistenciaRepository;
use App\Repositories\GrupoAlumnoRepository;
use App\Repositories\GrupoRepository;
use App\Repositories\InstitucionRepository;
use App\Repositories\PagoRepository;
use App\Repositories\RubroEvaluacionRepository;
use App\Repositories\SesionRepository;
use App\Repositories\SuscripcionRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositorios — interfaz → implementación concreta
        $this->app->bind(UsuarioRepositoryInterface::class,        UsuarioRepository::class);
        $this->app->bind(InstitucionRepositoryInterface::class,    InstitucionRepository::class);
        $this->app->bind(GrupoRepositoryInterface::class,          GrupoRepository::class);
        $this->app->bind(SesionRepositoryInterface::class,         SesionRepository::class);
        $this->app->bind(AsistenciaRepositoryInterface::class,     AsistenciaRepository::class);
        $this->app->bind(RubroEvaluacionRepositoryInterface::class, RubroEvaluacionRepository::class);
        $this->app->bind(GrupoAlumnoRepositoryInterface::class,    GrupoAlumnoRepository::class);
        $this->app->bind(SuscripcionRepositoryInterface::class,    SuscripcionRepository::class);
        $this->app->bind(PagoRepositoryInterface::class,           PagoRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
