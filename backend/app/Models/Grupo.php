<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent — tabla: grupos
 * Representa el aula virtual que agrupa alumnos en torno a una materia.
 */
class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';

    protected $fillable = [
        'id_institucion',
        'id_docente',
        'nombre',
        'materia',
        'periodo',
        'no_alumnos',
        'codigo_inv',
    ];

    protected function casts(): array
    {
        return [
            'no_alumnos' => 'integer',
        ];
    }

    // Relaciones
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id_institucion');
    }

    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_docente', 'id_usuario');
    }

    public function grupoAlumnos()
    {
        return $this->hasMany(GrupoAlumno::class, 'id_grupo', 'id_grupo');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'id_grupo', 'id_grupo');
    }

    public function alumnos()
    {
        return $this->belongsToMany(
            Usuario::class,
            'grupo_alumnos',
            'id_grupo',
            'id_alumno',
            'id_grupo',
            'id_usuario'
        );
    }
}