<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent — tabla: instituciones
 * Representa cada institución/espacio registrado por un Docente.
 */
class Institucion extends Model
{
    use HasFactory;

    protected $table = 'instituciones';
    protected $primaryKey = 'id_institucion';

    protected $fillable = [
        'id_docente',
        'nombre',
        'logo',
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_docente', 'id_usuario');
    }

    public function rubrosEvaluacion()
    {
        return $this->hasMany(RubroEvaluacion::class, 'id_institucion', 'id_institucion');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_institucion', 'id_institucion');
    }
}