<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cursos_participante extends Model
{
    use HasFactory;

    protected $fillable = [
        'participante_id',
        'curso_id',
        'acreditado',
        'calificacion',
        'comentarios',
        'numero_registro'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id');
    }
}
