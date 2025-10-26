<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Participante extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plantel',
        'titulo',
        'horas',
        'puesto',
        'nivelestudios',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cursos_participantes', 'participante_id', 'curso_id');
    }

    // Relaciones para Diplomados
    public function diplomados()
    {
        return $this->belongsToMany(Diplomado::class, 'solicitud_participante', 'participante_id', 'diplomado_id');
    }

    public function solicitudDocentes()
    {
        return $this->hasMany(solicitud_docente::class);
    }

    public function solicitudes()
    {
        return $this->belongsToMany(Diplomado::class, 'solicitud_docentes', 'participante_id', 'diplomado_id');
    }

    public function calificacionesModulos()
    {
        return $this->hasMany(Calificacion_modulo::class);
    }
}
