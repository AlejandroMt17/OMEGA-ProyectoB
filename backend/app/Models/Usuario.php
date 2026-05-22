<?php

/*
 * ============================================================
 * Modelo Eloquent — tabla: usuarios
 * MDB-OMEGA-DD-01 §4.1 | MPL-OMEGA-05 §6.3
 * ============================================================
 * Roles:
 *   1 = Docente — gestiona instituciones, grupos, sesiones
 *   2 = Alumno  — registra asistencia, consulta progreso
 *
 * Convenciones (MDB-OMEGA-01 §3.4):
 *   - PK: id_usuario
 *   - Contraseña: campo 'contrasenia' (no 'password')
 *   - Timestamps: habilitados
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'ap_pat',
        'ap_mat',
        'email',
        'contrasenia',
        'rol',
    ];

    protected $hidden = [
        'contrasenia',
    ];

    /**
     * Alias para que Laravel Sanctum/Auth use 'contrasenia' como 'password'.
     * (MPL-OMEGA-05 §6.3 — Manejo de Excepciones, autenticación segura)
     */
    public function getAuthPassword(): string
    {
        return $this->contrasenia;
    }

    protected function casts(): array
    {
        return [
            'rol'         => 'integer',
            'contrasenia' => 'hashed',
        ];
    }

    // ─── Helpers de rol ───────────────────────────────────────────────────

    /** RF-10 — Verifica si el usuario es Docente (rol = 1). */
    public function isDocente(): bool
    {
        return $this->rol === 1;
    }

    /** RF-10 — Verifica si el usuario es Alumno (rol = 2). */
    public function isAlumno(): bool
    {
        return $this->rol === 2;
    }

    // ─── Relaciones ───────────────────────────────────────────────────────

    /** Instituciones del docente (RF-57, RF-60). */
    public function instituciones()
    {
        return $this->hasMany(Institucion::class, 'id_docente', 'id_usuario');
    }

    /** Grupos del docente (RF-58). */
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_docente', 'id_usuario');
    }

    /** Suscripción activa del docente (RF-79, RF-80). */
    public function suscripcion()
    {
        return $this->hasOne(Suscripcion::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Vinculaciones del alumno a grupos (RF-11, RF-19).
     * Tabla intermedia: grupo_alumnos (MDB-OMEGA-DD-01 §4.5)
     */
    public function grupoAlumnos()
    {
        return $this->hasMany(GrupoAlumno::class, 'id_alumno', 'id_usuario');
    }

    /** Asistencias del alumno (RF-31, RF-32). */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_alumno', 'id_usuario');
    }
}
