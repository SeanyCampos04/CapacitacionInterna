<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class encuesta extends Model
{
    use HasFactory;
    protected $fillable = [
        'curso_id',
        'participante_id',
        'evento_expectativas',
        'evento_objetivo',
        'instructor_dudas',
        'contenidos_material',
        'contenidos_curso',
        'condiciones_adecuadas',
        'personal_organizador',
        'instructor_habilidad',
        'instructor_exposicion',
        'instructor_aclara',
        'competencias_desarrolladas',
        'competencias_adquiridas',
        'competencias_comprension',
        'participacion',
        'comentario',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }
}
